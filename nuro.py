import os
from operator import itemgetter
import csv
import re
import threading
from bs4 import BeautifulSoup
import requests
import sys 

class gomezScraper():
	"""Keynote.com Web Scraper Class"""
	
	maplink = ''
	scraperFileMaps = {}
	scrapingLinks = []
	year = ''
	week = ''
	month = ''
	midURL = ''
	scrapedData = []
	def __init__(self):

		# mainURL = 'https://www.graypools.com/fund-manager/updated/1/'
		mainURL = 'https://www.jobsbank.gov.sg/ICMSPortal/portlets/JobBankHandler/SearchResult.do?tabSelected=CATEGORY&aTabFunction=aTabFunction&Function=Accounting%20%2F%20Auditing%20%2F%20Taxation'
		r  = requests.post(mainURL,data='{actionForm.checkValidRequest}=YES')
		data = r.text
		print data
		# soup = BeautifulSoup(data)

		# results = soup.find('table');
		# # results = results.find('tbody')
		# for y,x in enumerate(results.find_all('tr')):
		# 	if y != 0:
		# 		anchor = x.find('a')
		# 		cityLink = anchor['href']
		# 		print cityLink
				# r  = requests.get(mainURL)
				# data = r.text
				# soup = BeautifulSoup(data)

				# if city and state and company_name and company_link:
				# 	with open('fund.csv', 'a+') as csvfile:
				# 		wrObj = csv.writer(csvfile, delimiter=',',quotechar='"', quoting=csv.QUOTE_MINIMAL)
				# 		wrObj.writerow([company_name, company_link, city, state]);
						


	def getPerformanceIndexs(self):

		for mapping in self.scraperFileMaps:

			

			benchmarks = soup.find_all(attrs={'class': re.compile(r".*\bbmtab\b.*")})

			for x in benchmarks:
				benchmarkID = x.find("a")["id"]
				btype = x.find("a").text
				url = 'http://benchmarks.compuwareapm.com/APM-Benchmarks/restapi/details/'+ benchmarkID +'/?subon=0'
				print url
				r  = requests.get(url)

				data = r.text

				soup = BeautifulSoup(data)


				table = soup.find(attrs={'class': re.compile(r".*\bresulttable\b.*")})
				tbody = table.find('tbody')
				print '----------------------------------------------------------------------------------------'
				for tr in tbody.find_all('tr'):
					cols = tr.find_all('td')
					participant = cols[0].text;
					rResponse = cols[1].text;
					Response = cols[2].text
					rAvailabilty = cols[3].text;
					Availabilty = cols[4].text
					rConsistency = cols[5].text;
					Consistency = cols[6].text
					with open('gomezData.csv', 'a+') as csvfile:
						wrObj = csv.writer(csvfile, delimiter=',',quotechar='"', quoting=csv.QUOTE_MINIMAL)
						wrObj.writerow([Country , Industry , Sector , btype , participant, rResponse , Response , rAvailabilty , rAvailabilty , Availabilty , rConsistency , Consistency]);
					print '| ',  Country , ' | ' , Industry , ' | ' , Sector, ' | ' , btype,  ' | ' , participant ,' | ' , rResponse , ' | ' , Response , ' | ' , rAvailabilty , ' | ' , rAvailabilty ,' |' , Availabilty ,' |' , rConsistency ,' |' , Consistency ,' |'
				print '----------------------------------------------------------------------------------------'

				pass
		# for link in soup.find_all('megamenu_main'):
		# 	if 'www.keynote.com/performance-indexes/' in link.get('href'):
		# 		self.maplink = link.get('href').replace('http://www.keynote.com/performance-indexes/','')
		# 		scrpFile = self.scraperFileMaps[self.maplink]
		# 		scrpLnk = self.midURL + scrpFile
		# 		if scrpLnk not in self.scrapingLinks:
		# 			self.scrapingLinks.append(scrpLnk)
		# 			self.dataScraper(scrpLnk)
		# 		else:
		# 			print "Link already scraped " + scrpLnk
		# 		# self.scrapingLinks.append(scrpLnk)

		# print "\nInitiating threading to scrape the data..."
		# pass

	def dataScraper(self,url):
		if __name__ == "__main__":
			directory = self.year+'-'+self.month+'-'+self.week;
			if not os.path.exists(directory):
				os.makedirs(directory)
			binarySemaphore = threading.Semaphore(1)
			CrawlerThread(binarySemaphore,url,self.year,self.month,self.week,self.maplink).start()
			

			

class CrawlerThread(threading.Thread):
	tdData = []
	def __init__(self, binarySemaphore, url, year,month,week,cate):
		print "crawling the data"
		self.category = cate
		self.binarySemaphore = binarySemaphore
		self.url = url
		self.year = year
		self.week = week
		self.month = month
		self.threadId = hash(self)
		threading.Thread.__init__(self)
		self.run()
	def run(self):
		print "Thread #%d: Reading from %s" %(self.threadId, self.url)
		r = requests.get(self.url)  
		data = r.text
		soup = BeautifulSoup(data)
		print "Writing the CSV Data..."
		directory = self.year+'-'+self.month+'-'+self.week;
		with open(directory+'/'+self.category+'.csv', 'wb') as csvfile:
			wrObj = csv.writer(csvfile, delimiter=',',quotechar='"', quoting=csv.QUOTE_MINIMAL)
			tdRows = []
			for idxm, rows in enumerate(soup.tbody.find_all('tr')):
				rowsvar = rows.find_all('td')
				for idx, td in enumerate(rowsvar):
					self.tdData.append(td.string)
				tdRows.append(self.tdData)
				self.tdData = []
				rowsvar = []
			
			# Sorted array based on the response time which is the actual rank according to keynote
			tdRows.sort(key=lambda x: float(x[2]))
			for idxm, row in enumerate(tdRows):
				colsData = []
				for idx, cols in enumerate(row):
					if not cols: 
						colsData.append(idxm+1)
					else: 
						colsData.append(cols)
				wrObj.writerow(colsData)
				colsData = []
			csvfile.close();
			tdRows = []
		self.binarySemaphore.release()
	pass


gomezScraper()