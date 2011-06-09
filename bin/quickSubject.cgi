#! /usr/bin/env python

import cgi
form = cgi.FieldStorage()
subject = form.getfirst("subject")

print "Content-Type: text/html" 
print ""

if subject == "Disabled Type E":
  f = open('bodytemplates/ad1.txt', 'r')
  text = f.read()
  print text
  f.close()

if subject == "Password Reset (Phone)":
  f = open('bodytemplates/password_reset.txt', 'r')
  text = f.read()
  print text
  f.close() 

if subject == "Password Reset (Walk-in)":
  f = open('bodytemplates/password_reset.txt', 'r')
  text = f.read()
  print text
  f.close()
 
if subject == "First Time OAM (Phone)":
  f = open('bodytemplates/first_time_oam.txt', 'r')
  text = f.read()
  print text
  f.close()
  
if subject == "First Time OAM (Walk-in)":
  f = open('bodytemplates/first_time_oam.txt', 'r')
  text = f.read()
  print text
  f.close()

if subject == "Miscellaneous Question (Phone)":
  f = open('bodytemplates/misc_question.txt', 'r')
  text = f.read()
  print text
  f.close() 
 
if subject == "Miscellaneous Question (Walk-in)":
  f = open('bodytemplates/misc_question.txt', 'r')
  text = f.read()
  print text
  f.close() 
 
if subject == "Game Console Registration":
  f = open('bodytemplates/game_console.txt', 'r')
  text = f.read()
  print text
  f.close()

if subject == "[WRKBENCH] Student Virus Scan/Reinstall OS":
  f = open('bodytemplates/student_virus.txt', 'r')
  text = f.read()
  print text
  f.close()

if subject == "[WRKBENCH] Staff/Faculty Reimage":
  f = open('bodytemplates/staff_faculty_reimage.txt', 'r')
  text = f.read()
  print text
  f.close()

if subject == "[WRKBENCH] Staff/Faculty Personal":
  f = open('bodytemplates/staff_faculty_reimage.txt', 'r')
  text = f.read()
  print text
  f.close()

if subject == "[WRKBENCH] Software Install":
  f = open('bodytemplates/software_install.txt', 'r')
  text = f.read()
  print text
  f.close()

if subject == "[WRKBENCH] Appointment":
  f = open('bodytemplates/staff_faculty_reimage.txt', 'r')
  text = f.read()
  print text
  f.close()

if subject == "Software Order Form":
  f = open('bodytemplates/software_order.txt', 'r')
  text = f.read()
  print text
  f.close()
  
else:
  print ""

