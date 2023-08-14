''' 2023-08-14
車用疲勞駕駛專題-眼睛閉合監測

使用方法: 
(1)先開啟Arduino IDE的ESP32 Camera範例程式(WebServer那一支程式)
(2)再開啟這支Python疲勞駕駛偵測程式
'''

import numpy as np
from scipy.spatial import distance as dist  
import dlib
import cv2
from PIL import ImageFont, ImageDraw, Image
from urllib.request import urlopen
import time


#ESP32-CAM http webserver: 輸入你的webserver IP 
url="http://192.168.212.99:81/stream"
CAMERA_BUFFRER_SIZE=4096
record_width, record_height = 640,480

#讀取影像串流
def read_stream():
    global bts

    bts+=stream.read(CAMERA_BUFFRER_SIZE)
    jpghead=bts.find(b'\xff\xd8') 
    jpgend=bts.find(b'\xff\xd9')  
    print("jpghead, jpgend", jpghead,jpgend)
    img = None
    height,width = 0,0
    
    if jpghead>-1 and jpgend>-1:
        jpg=bts[jpghead:jpgend+2]
        bts=bts[jpgend+2:]
            
        try:
            img=cv2.imdecode(np.frombuffer(jpg,dtype=np.uint8),cv2.IMREAD_UNCHANGED)    
            height,width=img.shape[:2]
            img=cv2.resize(img,(record_width, record_height))
            print(img.shape)
        except:
            img = None
            print("no data received.")

    return img,(width,height)

# 獲取圖像内的左眼、右眼對應的關鍵點集
def getEYE(image,rect):
    landmarks=predictor(image, rect)
    # 關鍵點處理為(x,y)形式
    shape = np.matrix([[p.x, p.y] for p in landmarks.parts()])
    # 計算左眼、右眼關鍵點集
    leftEye = shape[42:48]   
    rightEye = shape[36:42]  
    return leftEye,rightEye

#計算眼睛的長寬比（小於0.25太小是閉眼或眨眼、超過0.25是睁眼）
def eye_aspect_ratio(eye):
    A = dist.euclidean(eye[1], eye[5])
    B = dist.euclidean(eye[2], eye[4])
    C = dist.euclidean(eye[0], eye[3])
    ear = (A + B) / (2.0 * C) 
    return ear
    
 # 计算左眼/右眼長與寬比
def earMean(leftEye,rightEye):   
    leftEAR = eye_aspect_ratio(leftEye)
    rightEAR = eye_aspect_ratio(rightEye)
    ear = (leftEAR + rightEAR) / 2.0  
    return ear

def drawEye(eye):
    eyeHull = cv2.convexHull(eye)
    cv2.drawContours(frame, [eyeHull], -1, (0, 255, 0), 1)


RationTresh = 0.25 #綜橫比比例<0.25為閉眼
ClosedThresh = 3 #次數>3為閉眼

# 計數器
COUNTER = 0
# 模型初始化
detector = dlib.get_frontal_face_detector()
predictor = dlib.shape_predictor("shape_predictor_68_face_landmarks.dat")
print("模型初始化...ok")


# 初始化攝影機
cap = cv2.VideoCapture(0,cv2.CAP_DSHOW) 

bts=b''
if __name__ == "__main__":

    stream=urlopen(url)
    
    frameID = 0
    img = None

    while True:
        
        # read video frame
        frame, (width, height) = read_stream() #讀取影像串流
        

        # get face
        boxes = detector(frame, 0)
        #循環看每一个boxes内對象
        for b in boxes:
            leftEye,rightEye=getEYE(frame,b)  
            ear=earMean(leftEye,rightEye)  #計算左眼、右眼的縱横比均值
            # 判断眼睛的高宽比（縱横比，ear),小於0.3（EYE_AR_THRESH），認為閉眼了
            # 閉眼可能是正常眨眼，也可能是疲劳了，繼續計算閉眼的時長
            if ear < RationTresh:
                COUNTER += 1  # 没檢测到一次，將【計數器】加 1
                # 【计数器】足够大，說明閉眼時間足够長 ，認為疲劳了
                if COUNTER >= ClosedThresh:
                    # 發警告dangerous訊息
                    cv2.putText(frame, "!!!!DANGEROUS!!!!", (50, 200),
                        cv2.FONT_HERSHEY_SIMPLEX, 2, (0, 0, 255), 2)
            # 否则（對應寬高比大於0.25），技術器清零、解除疲勞標誌
            else:
                COUNTER = 0       #【計數器】清零
            # 繪製眼框（眼睛的包圍框）
            drawEye(leftEye)
            drawEye(rightEye)
            # 顯示EAR值（eye_aspect_ratio)
            cv2.putText(frame, "EAR: {:.2f}".format(ear), (0, 30),
                cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 255, 0), 2)
         # 顯示结果
        cv2.imshow("Frame", frame)
        # 按下Esc键，退出。ESC键的ASCII码为27
        if cv2.waitKey(1) == 27:
            break
    cv2.destroyAllWindows()
    cap.release()