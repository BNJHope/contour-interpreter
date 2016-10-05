This is a parser for the Contour project. It parses a basic function syntax for spreadsheets so that operations on tags can be performed.

Examples:
Variable Declarations & Assignments:
 - Variables can be assigned with arithmetical/boolean operators and expressions and also tag expressions to link to other cells
    on other sheets.
 - For an integer "num" with value 7:
  let num = 7
  
 - For a string "name" with value "Alice":
  let name = "alice"
  
 - For a variable "cellVal" that links to the spreadsheet cell referenced as #(row, col) + 7
 let cellVal = #(row, col) + 7
 
Conditional Statements :
  -Multiline for clear understanding
  -Can use boolean operators to link statements.
  -If statements can nest
  
  -For an if statement to see if the a string variable "name" is equal to "alice" and if so then set variable "correctName" to   true and if not then set name to the value of cell (2, name)
   if (name = "alice")
   then let correctName = true
   else then let name = #(2, name)
  
  -Another example if statement. Notice how & and 'and' can be used in the exact same way and will produce the same result.
  The return keyword returns the result of the function
    if (name = "alice" & age = 22)
   then return 1
   else if (name = "bob" and age = 23)
   then return 2
   else then return 0
   
If there are any errors in the function code then it is outputted to the user, giving the type of error and the location of it also.
