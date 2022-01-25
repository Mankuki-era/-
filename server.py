import pymysql.cursors
import datetime
import time
import sys
import socket

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.bind(("", 1235))  # IPとポート番号を指定します
s.listen(5)

cnx = pymysql.connect(host='localhost', port=3308, user='root', password='root', db='entryexit')

while True:
    clientsocket, address = s.accept()

    number = clientsocket.recv(1024).decode("utf-8")
    temp = float(clientsocket.recv(1024).decode("utf-8"))
    entrytime = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')

    text = "INSERT INTO `entryexit`.`logs` (`number`, `temp`, `created_at`) VALUES ('{0}', '{1}', '{2}')"
    insert_query = (text.format(number, temp, entrytime))
    cursor = cnx.cursor()
    cursor.execute(insert_query)
    cnx.commit()

    clientsocket.close()

cursor.close()
cnx.close()