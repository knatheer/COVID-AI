# Detecting Objects on Image with OpenCV deep learning library
#
# Algorithm:
# Reading RGB image --> Getting Blob --> Loading YOLO v3 Network -->
# --> Implementing Forward Pass --> Getting Bounding Boxes -->
# --> Non-maximum Suppression --> Drawing Bounding Boxes with Labels
#
# Result:
# Window with Detected Objects, Bounding Boxes and Labels


# Importing needed libraries
import numpy as np
import cv2
import time
import os
import glob



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

base_dir = 'C://xampp//htdocs//covid//'
#base_dir = '/var/www/html/covid/'

received_folder = os.path.join(base_dir, 'received')
new_folder = os.path.join(base_dir, 'new')
processed_folder = os.path.join(base_dir, 'processed')
result_folder = os.path.join(base_dir,'result')
#print(received_folder)
#print(new_folder)
#print(processed_folder)
#print(result_folder)

"""
Start of:
Loading YOLO v4 network
"""

# Loading COVID class labels from file
# Opening file
with open('yolo-covid-data/covid.names') as f:
    # Getting labels reading every line
    # and putting them into the list
    labels = [line.strip() for line in f]

# Loading pre-trained YOLO v4 Objects Detector
# with the help of 'dnn' library from OpenCV
network = cv2.dnn.readNetFromDarknet('yolo-covid-data/yolo-covid.cfg',
                                     'yolo-covid-data/yolo-covid.weights')

# Getting list with names of all layers from YOLO v3 network
layers_names_all = network.getLayerNames()


# Getting only output layers' names that we need from YOLO v4 algorithm
# with function that returns indexes of layers with unconnected outputs
layers_names_output = \
    [layers_names_all[i[0] - 1] for i in network.getUnconnectedOutLayers()]


# Setting minimum probability to eliminate weak predictions
probability_minimum = 0.5

# Setting threshold for filtering weak bounding boxes
# with non-maximum suppression
threshold = 0.3

# Generating colours for representing every detected object
# with function randint(low, high=None, size=None, dtype='l')
colours = np.random.randint(0, 255, size=(len(labels), 3), dtype='uint8')


"""
End of:
Loading YOLO v3 network
"""

while True:
    """
    Start of:
    Implementing Forward pass
    """
    time.sleep(0.5)
    new_file_list = glob.glob(new_folder + "//*.*")
    if len(new_file_list) > 0:
        print("new file received")
        file_name_new = new_file_list[0]
        fname = os.path.basename(file_name_new)
        file_name = file_name_new.replace('new', 'received')
        # Getting spatial dimension of input image
        #Read the next image
        image_BGR = cv2.imread(file_name)
        image_BGR = resize_image(image_BGR)
        print("shape is")
        print(file_name)
        print(image_BGR.shape)
        #remove the file
        os.remove(file_name_new)
        #print(file_name)
        h, w = image_BGR.shape[:2]  # Slicing from tuple only first two elements

        # Getting blob from input image
        blob = cv2.dnn.blobFromImage(image_BGR, 1 / 255.0, (416, 416),
                                     swapRB=True, crop=False)



        # Implementing forward pass with our blob and only through output layers
        # Calculating at the same time, needed time for forward pass
        network.setInput(blob)  # setting blob as input to the network
        start = time.time()
        output_from_network = network.forward(layers_names_output)
        end = time.time()

        # Showing spent time for forward pass
        print('Objects Detection took {:.5f} seconds'.format(end - start))
        msg = ('Objects Detection took {:.5f} seconds'.format(end - start))

        """
        End of:
        Implementing Forward pass
        """


        """
        Start of:
        Getting bounding boxes
        """

        # Preparing lists for detected bounding boxes,
        # obtained confidences and class's number
        bounding_boxes = []
        confidences = []
        class_numbers = []

        detected_result = ''

        # Going through all output layers after feed forward pass
        for result in output_from_network:
            # Going through all detections from current output layer
            for detected_objects in result:
                # Getting 80 classes' probabilities for current detected object
                scores = detected_objects[5:]
                # Getting index of the class with the maximum value of probability
                class_current = np.argmax(scores)
                # Getting value of probability for defined class
                confidence_current = scores[class_current]

                # # Check point
                # # Every 'detected_objects' numpy array has first 4 numbers with
                # # bounding box coordinates and rest 80 with probabilities for every class
                # print(detected_objects.shape)  # (85,)

                # Eliminating weak predictions with minimum probability
                if confidence_current > probability_minimum:
                    # Scaling bounding box coordinates to the initial image size
                    # YOLO data format keeps coordinates for center of bounding box
                    # and its current width and height
                    # That is why we can just multiply them elementwise
                    # to the width and height
                    # of the original image and in this way get coordinates for center
                    # of bounding box, its width and height for original image
                    box_current = detected_objects[0:4] * np.array([w, h, w, h])

                    # Now, from YOLO data format, we can get top left corner coordinates
                    # that are x_min and y_min
                    x_center, y_center, box_width, box_height = box_current
                    x_min = int(x_center - (box_width / 2))
                    y_min = int(y_center - (box_height / 2))

                    # Adding results into prepared lists
                    bounding_boxes.append([x_min, y_min, int(box_width), int(box_height)])
                    confidences.append(float(confidence_current))
                    class_numbers.append(class_current)

        """
        End of:
        Getting bounding boxes
        """


        """
        Start of:
        Non-maximum suppression
        """

        # Implementing non-maximum suppression of given bounding boxes
        # With this technique we exclude some of bounding boxes if their
        # corresponding confidences are low or there is another
        # bounding box for this region with higher confidence

        # It is needed to make sure that data type of the boxes is 'int'
        # and data type of the confidences is 'float'
        # https://github.com/opencv/opencv/issues/12789
        results = cv2.dnn.NMSBoxes(bounding_boxes, confidences,
                                   probability_minimum, threshold)

        """
        End of:
        Non-maximum suppression
        """


        """
        Start of:
        Drawing bounding boxes and labels
        """

        # Defining counter for detected objects
        counter = 1

        # Checking if there is at least one detected object after non-maximum suppression
        if len(results) > 0:
            # Going through indexes of results
            for i in results.flatten():
                # Showing labels of the detected objects
                print('Object {0}: {1}'.format(counter, labels[int(class_numbers[i])]))

                # Incrementing counter
                counter += 1

                # Getting current bounding box coordinates,
                # its width and height
                x_min, y_min = bounding_boxes[i][0], bounding_boxes[i][1]
                box_width, box_height = bounding_boxes[i][2], bounding_boxes[i][3]

                # Preparing colour for current bounding box
                # and converting from numpy array to list
                colour_box_current = colours[class_numbers[i]].tolist()

                # # # Check point
                # print(type(colour_box_current))  # <class 'list'>
                # print(colour_box_current)  # [172 , 10, 127]

                # Drawing bounding box on the original image
                cv2.rectangle(image_BGR, (x_min, y_min),
                              (x_min + box_width, y_min + box_height),
                              colour_box_current, 2)

                # Preparing text with label and confidence for current bounding box
                text_box_current = '{}: {:.4f}'.format(labels[int(class_numbers[i])],
                                                       confidences[i])

                # Putting text with label and confidence on the original image
                cv2.putText(image_BGR, text_box_current, (x_min, y_min - 5),
                            cv2.FONT_HERSHEY_COMPLEX, 0.7, colour_box_current, 2)


                detected_result += text_box_current + "<br>"


        # Comparing how many objects where before non-maximum suppression
        # and left after
        print()
        print('Total objects been detected:', len(bounding_boxes))
        print('Number of objects left after non-maximum suppression:', counter - 1)

        msg += ('<br>Number of detected objects:' + str(counter - 1))
        msg += '<br>' + detected_result

        """
        End of:
        Drawing bounding boxes and labels
        """


        # Showing Original Image with Detected Objects
        # Giving name to the window with Original Image
        # And specifying that window is resizable

        cv2.imwrite(os.path.join(processed_folder, fname), image_BGR)

        f = open(os.path.join(result_folder, fname + ".txt"), "w")
        f.write(msg)
        f.close()

        print(msg)
