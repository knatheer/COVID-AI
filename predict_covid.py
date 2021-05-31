# Detecting Objects on Image with OpenCV deep learning library
#
# Algorithm:
# I will describe later.
# Importing needed libraries
import numpy as np
import cv2
import time
import os
from tensorflow.keras.models import load_model

import glob

import mysql.connector



def resize_image(src_img):
    # identify the percent by which the image is resized

    src_width = src_img.shape[1]
    if src_width < 750:
        return src_img

    scale_percent = (750 / src_width) * 100
    scale_percent = round(scale_percent)
    #scale_percent = 50

    # calculate the 50 percent of original dimensions
    width = int(src_img.shape[1] * scale_percent / 100)
    height = int(src_img.shape[0] * scale_percent / 100)
    # dsize
    dsize = (width, height)
    # resize image
    output = cv2.resize(src_img, dsize, interpolation=cv2.INTER_AREA)

    return output


mydb = mysql.connector.connect(
#    host="127.0.0.1",
#    user="root",
#    password="",
#    database="covid"
  host="127.0.0.1",
  user="natheer",
  password="pass",
  database="covid"
)



#base_dir = 'C://xampp//htdocs//covid//'
base_dir = '/var/www/html/covid/'

received_folder = os.path.join(base_dir, 'received')
new_folder = os.path.join(base_dir, 'new')
processed_folder = os.path.join(base_dir, 'processed')
result_folder = os.path.join(base_dir,'result')


#Load  CNN Model
#classifier = load_model('C://Users//Admin//PycharmProjects//COVID-AI//models//mobile_net.h5')
classifier = load_model('/root/tools/COVID-AI/models/mobile_net.h5')

#Start the loop where we read image by and image and perform prediction
i = 0
while True:
    #So we a do a check every 0.5 second to see if there is a new image received for processing
    time.sleep(0.5)
    mydb.reconnect()
    mycursor = mydb.cursor()
    i += 1
    sql = "SELECT img_name FROM requests WHERE status ='new'"
    mycursor.execute(sql)
    myresult = mycursor.fetchall()
    #There is image receved
    if len(myresult) > 0:
        print("new file received")
        result = myresult[0]
        file_name_new = result[0]
        file_name = os.path.join(received_folder, file_name_new)
        input_im = cv2.imread(file_name)
        input_original = input_im.copy()
        input_original = cv2.resize(input_original, None, fx=0.5, fy=0.5, interpolation=cv2.INTER_LINEAR)
        input_im = cv2.resize(input_im, (224, 224), interpolation=cv2.INTER_LINEAR)
        input_im = input_im / 255.
        input_im = input_im.reshape(1, 224, 224, 3)
        # Get Prediction
        start = time.clock()
        prediction_result = classifier.predict(input_im, 1, verbose=1)
        end = time.clock()
        pred_time = end-start
        res = np.argmax(prediction_result, axis=1)
        prediction_value = res[0]
        confidence = prediction_result[0][prediction_value]
        msg = 'NORMAL'
        if prediction_value == 0:
            msg = 'COVID'
        sql = "UPDATE requests SET status = %s, result = %s, process_time = %s WHERE img_name = %s"
        val = ("processed", msg, 0.0, file_name_new)
        mycursor.execute(sql, val)
        mydb.commit()
        print(msg, str(confidence), str(pred_time))
    mycursor.close()
    mydb.close()
