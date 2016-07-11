The grammar for declaring basic functions in the boxes in the Evergreen application.

statement := command || conditional_full

command := variable_declaration || return_statement

variable_declaration := LET variable_name EQUALS value;

conditional_full := conditional_if conditional_then (conditional_else_if conditional_then)* (conditional_else)?
conditional_if:= IF boolean_expression_container (boolean_operator boolean_expression_container)*
conditional_then:= THEN command
conditional_else_if := ELSE conditional_if

boolean_expression_container := OPENBRACKET boolean_expression CLOSEBRACKET

boolean_expression := comparative_value comparator comparative_value
comparative_value = variable_name || value
comparator = LESSTHAN || GREATERTHAN || EQUALS

OR := |
AND := &
LET := let
IF := if
THEN := then
ELSE = else
EQUALS := =