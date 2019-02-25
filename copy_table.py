oristr = "Bill No,Bill Year,Short Title of Bill	Ministry,Member Name,Date of Introduction,Bill Category,Status,URL for Bill As Introduced"
splitori = oristr.split(',')
for i in range( len(splitori)):
	temp = splitori[i]
	tempsplit = temp.split(' ')
	tempconv = "_".join(tempsplit)
	# if(i==0 or i==1):
	tempconv += " varchar(255)"	
	# else:
	# 	tempconv += " int"
	splitori[i] = tempconv

print(', '.join(splitori))