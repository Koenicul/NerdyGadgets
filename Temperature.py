import socket
import mysql.connector


conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="nerdygadgets"
)

cursor = conn.cursor()


s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect(("10.80.17.1", 5000))

while True:
    for _ in range(10):
        try:
            temp_str = s.recv(1024).decode("utf-8").strip()
            print(f"temp_str: {temp_str}")

            temp = float(temp_str)

            cursor.execute('''
                INSERT INTO coldroomtemperatures (ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, ValidTo)
                VALUES (5, NOW(), %s, NOW(), '9999-12-31 23:59:59')
            ''', (temp,))
            conn.commit()

            print("Data toegevoegd aan de database")
        except ValueError:
            print("Invalid temperature value received.")
        except mysql.connector.Error as err:
            print(f"Error inserting data into the database: {err}")


    archive_query = """
        INSERT INTO coldroomtemperatures_archive (ColdRoomTemperatureID, ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, ValidTo)
        SELECT ColdRoomTemperatureID, ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, NOW() FROM coldroomtemperatures
        WHERE ColdRoomTemperatureID < (SELECT MAX(ColdRoomTemperatureID) FROM coldroomtemperatures);
    """
    cursor.execute(archive_query)


    delete_query = """
        DELETE FROM coldroomtemperatures
        WHERE ColdRoomTemperatureID < (SELECT MAX(ColdRoomTemperatureID) FROM coldroomtemperatures);
    """
    cursor.execute(delete_query)

    conn.commit()