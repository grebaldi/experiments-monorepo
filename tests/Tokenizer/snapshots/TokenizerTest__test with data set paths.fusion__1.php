<?php return '
TYPE                           S_IDX S_ROW S_COL E_IDX E_ROW E_COL VALUE
STRING_START                   0     0     0     0     0     0     \'
STRING_VALUE                   1     0     1     20    0     20    Top level path with 
STRING_ESCAPE                  21    0     21    21    0     21    \\
STRING_ESCAPED_CHARACTER       22    0     22    22    0     22    \'
STRING_VALUE                   23    0     23    28    0     28    spaces
STRING_ESCAPE                  29    0     29    29    0     29    \\
STRING_ESCAPED_CHARACTER       30    0     30    30    0     30    \'
STRING_VALUE                   31    0     31    52    0     52     and <special> chars!?
STRING_END                     0     0     0     0     0     0     \'
WHITESPACE                     54    0     54    54    0     54     
BRACKET_CURLY_OPEN             55    0     55    55    0     55    {
WHITESPACE                     56    0     56    60    1     3          
STRING_START                   61    1     4     61    1     4     \'
STRING_VALUE                   62    1     5     115   1     58    Nested path with spaces and prototype(special) chars!?
STRING_END                     61    1     4     61    1     4     \'
WHITESPACE                     117   1     60    117   1     60     
OPERATOR_ASSIGN_VALUE          118   1     61    118   1     61    =
WHITESPACE                     119   1     62    119   1     62     
KEYWORD_NULL                   120   1     63    123   1     66    null
WHITESPACE                     124   1     67    124   1     67     
BRACKET_CURLY_CLOSE            125   2     0     125   2     0     }
WHITESPACE                     126   2     1     127   3     0       
IDENTIFIER                     128   4     0     135   4     7     describe
SEPARATOR_PATH                 136   4     8     136   4     8     .
STRING_START                   137   4     9     137   4     9     \'
STRING_VALUE                   138   4     10    172   4     44    Top level path accessed by operator
STRING_END                     137   4     9     137   4     9     \'
WHITESPACE                     174   4     46    174   4     46     
BRACKET_CURLY_OPEN             175   4     47    175   4     47    {
WHITESPACE                     176   4     48    180   5     3          
IDENTIFIER                     181   5     4     182   5     5     it
SEPARATOR_PATH                 183   5     6     183   5     6     .
STRING_START                   184   5     7     184   5     7     \'
STRING_VALUE                   185   5     8     216   5     39    Nested path accessed by operator
STRING_END                     184   5     7     184   5     7     \'
WHITESPACE                     218   5     41    218   5     41     
OPERATOR_ASSIGN_VALUE          219   5     42    219   5     42    =
WHITESPACE                     220   5     43    220   5     43     
KEYWORD_NULL                   221   5     44    224   5     47    null
WHITESPACE                     225   5     48    225   5     48     
BRACKET_CURLY_CLOSE            226   6     0     226   6     0     }
END_OF_FILE                    226   6     0     226   6     0     
';
