DELETE n1 FROM `publicationrecords` n1, `publicationrecords` n2 
WHERE n1.userid = n2.userid AND n1.title = n2.title AND n1.year = n2.year AND (n1.volume = n2.volume OR n1.volume IS NULL) AND (n1.issue = n2.issue OR n1.issue IS NULL) AND (n1.startpage = n2.startpage  OR n1.startpage IS NULL) AND n1.id > n2.id
