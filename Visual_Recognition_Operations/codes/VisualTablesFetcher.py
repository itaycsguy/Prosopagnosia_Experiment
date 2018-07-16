import pymysql,os,xlsxwriter,glob,time
from datetime import date

# wrote by Itay Guy
class VisualTablesFetcher():
	def __init__(self):
		self._conn = pymysql.connect(	
										host='31.220.20.208',
										user='u382083003_recog',
										password='Sdydyt77',                             
										db='u382083003_recog',
										charset='utf8mb4'
									) # connect to mysql endpoint using utf8mb4 for hebrew fetching
		
	def exportAll(self):
		folderPath = self.__createFolder() # create folder's tree follow some strategy
		self._conn.cursor().execute("SET SESSION CHARACTER_SET_RESULTS = utf8mb4;")
		self._conn.cursor().execute("SET NAMES utf8;")
		with self._conn.cursor() as cursor:
			sql_subjects = "SELECT * FROM subjects"
			cursor.execute(sql_subjects)
			self.__writeXLSX(cursor,"subjects",folderPath)
			sql_famous = "SELECT * FROM famous"
			cursor.execute(sql_famous)
			self.__writeXLSX(cursor,"famous",folderPath)
			sql_similarity = "SELECT * FROM similarity"
			cursor.execute(sql_similarity)
			self.__writeXLSX(cursor,"similarity",folderPath)
			sql_shoes_1 = "SELECT * FROM shoes_1"
			cursor.execute(sql_shoes_1)
			self.__writeXLSX(cursor,"shoes_1",folderPath)
			sql_shoes_2 = "SELECT * FROM shoes_2"
			cursor.execute(sql_shoes_2)
			self.__writeXLSX(cursor,"shoes_2",folderPath)
			sql_shoes_3 = "SELECT * FROM shoes_3"
			cursor.execute(sql_shoes_3)
			self.__writeXLSX(cursor,"shoes_3",folderPath)
			sql_global = "SELECT * FROM global"
			cursor.execute(sql_global)
			self.__writeXLSX(cursor,"global",folderPath)
			sql_local = "SELECT * FROM local"
			cursor.execute(sql_local)
			self.__writeXLSX(cursor,"local",folderPath)
			sql_faces_1 = "SELECT * FROM faces_1"
			cursor.execute(sql_faces_1)
			self.__writeXLSX(cursor,"faces_1",folderPath)
			sql_faces_2 = "SELECT * FROM faces_2"
			cursor.execute(sql_faces_2)
			self.__writeXLSX(cursor,"faces_2",folderPath)
			sql_faces_3 = "SELECT * FROM faces_3"
			cursor.execute(sql_faces_3)
			self.__writeXLSX(cursor,"faces_3",folderPath)
			sql_expressions = "SELECT * FROM expressions"
			cursor.execute(sql_expressions)
			self.__writeXLSX(cursor,"expressions",folderPath)
			print("successfully done.")
			
	def __writeXLSX(self,cursor,table,folder):
		filePath = folder + "\\" + str(table) + ".xlsx"
		workbook = xlsxwriter.Workbook(filePath)
		worksheet = workbook.add_worksheet()
		for r,row in enumerate(cursor):
			for c,col in enumerate(row):
				worksheet.write(r,c,col)
					

	def __createFolder(self):
		path = os.path.dirname(os.path.abspath(__file__)) + "\\.." + "\\visual_recognitions"
		try:
			os.makedirs(path)
		except:
			pass
		folderTarget = "subject_tables_" + date.today().strftime("%d/%m/%Y").replace("/","-") + "_" + time.strftime("%H:%M:%S").replace(":","-") + "_"
		folders = []
		for fol in os.listdir(path):
			if folderTarget in fol:
				folders.append(fol)
		folders = sorted(folders,reverse=True)
		count = 0
		if folders:
			count = int(folders[0][len(folderTarget):])+1
		else:
			count = 1
		fullPathTo = path + "\\" + folderTarget + str(count)
		os.makedirs(fullPathTo)
		return fullPathTo
				

if __name__ == "__main__":
	VisualTablesFetcher().exportAll()