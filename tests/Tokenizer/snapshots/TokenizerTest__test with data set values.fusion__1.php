<?php return '
TYPE                           S_IDX S_ROW S_COL E_IDX E_ROW E_COL VALUE
IDENTIFIER                     0     0     0     5     0     5     string
WHITESPACE                     6     0     6     6     0     6      
OPERATOR_ASSIGN_VALUE          7     0     7     7     0     7     =
WHITESPACE                     8     0     8     8     0     8      
STRING_START                   9     0     9     9     0     9     \'
STRING_VALUE                   10    0     10    21    0     21    Hello World!
STRING_END                     9     0     9     9     0     9     \'
WHITESPACE                     23    0     23    23    0     23     
IDENTIFIER                     24    1     0     30    1     6     integer
WHITESPACE                     31    1     7     31    1     7      
OPERATOR_ASSIGN_VALUE          32    1     8     32    1     8     =
WHITESPACE                     33    1     9     33    1     9      
NUMBER_INTEGER                 34    1     10    35    1     11    42
WHITESPACE                     36    1     12    36    1     12     
IDENTIFIER                     37    2     0     41    2     4     float
WHITESPACE                     42    2     5     42    2     5      
OPERATOR_ASSIGN_VALUE          43    2     6     43    2     6     =
WHITESPACE                     44    2     7     44    2     7      
NUMBER_FLOAT                   45    2     8     51    2     14    123.456
WHITESPACE                     52    2     15    52    2     15     
IDENTIFIER                     53    3     0     59    3     6     boolean
WHITESPACE                     60    3     7     60    3     7      
OPERATOR_ASSIGN_VALUE          61    3     8     61    3     8     =
WHITESPACE                     62    3     9     62    3     9      
KEYWORD_TRUE                   63    3     10    66    3     13    true
WHITESPACE                     67    3     14    67    3     14     
IDENTIFIER                     68    4     0     74    4     6     boolean
WHITESPACE                     75    4     7     75    4     7      
OPERATOR_ASSIGN_VALUE          76    4     8     76    4     8     =
WHITESPACE                     77    4     9     77    4     9      
KEYWORD_FALSE                  78    4     10    82    4     14    false
WHITESPACE                     83    4     15    83    4     15     
IDENTIFIER                     84    5     0     88    5     4     empty
WHITESPACE                     89    5     5     89    5     5      
OPERATOR_ASSIGN_VALUE          90    5     6     90    5     6     =
WHITESPACE                     91    5     7     91    5     7      
KEYWORD_NULL                   92    5     8     95    5     11    null
WHITESPACE                     96    5     12    96    5     12     
IDENTIFIER                     97    6     0     115   6     18    prototypeInvocation
WHITESPACE                     116   6     19    116   6     19     
OPERATOR_ASSIGN_VALUE          117   6     20    117   6     20    =
WHITESPACE                     118   6     21    118   6     21     
IDENTIFIER                     119   6     22    141   6     44    Vendor.Site:MyPrototype
WHITESPACE                     142   6     45    142   6     45     
IDENTIFIER                     143   7     0     152   7     9     expression
WHITESPACE                     153   7     10    153   7     10     
OPERATOR_ASSIGN_VALUE          154   7     11    154   7     11    =
WHITESPACE                     155   7     12    155   7     12     
EXPRESSION_START               156   7     13    157   7     14    ${
IDENTIFIER                     158   7     15    158   7     15    x
WHITESPACE                     159   7     16    159   7     16     
EXPRESSION_OPERATOR_ADD        160   7     17    160   7     17    +
WHITESPACE                     161   7     18    161   7     18     
IDENTIFIER                     162   7     19    162   7     19    y
EXPRESSION_END                 163   7     20    163   7     20    }
WHITESPACE                     164   7     21    164   7     21     
IDENTIFIER                     165   8     0     170   8     5     nested
SEPARATOR_PATH                 171   8     6     171   8     6     .
IDENTIFIER                     172   8     7     175   8     10    path
OPERATOR_ASSIGN_TYPE           176   8     11    176   8     11    :
WHITESPACE                     177   8     12    177   8     12     
IDENTIFIER                     178   8     13    183   8     18    string
WHITESPACE                     184   8     19    184   8     19     
OPERATOR_ASSIGN_VALUE          185   8     20    185   8     20    =
WHITESPACE                     186   8     21    186   8     21     
EXPRESSION_START               187   8     22    188   8     23    ${
IDENTIFIER                     189   8     24    197   8     32    EelHelper
PERIOD                         198   8     33    198   8     33    .
IDENTIFIER                     199   8     34    204   8     39    method
BRACKET_ROUND_OPEN             205   8     40    205   8     40    (
IDENTIFIER                     206   8     41    206   8     41    a
WHITESPACE                     207   8     42    207   8     42     
EXPRESSION_OPERATOR_DIVIDE     208   8     43    208   8     43    /
WHITESPACE                     209   8     44    209   8     44     
IDENTIFIER                     210   8     45    210   8     45    b
COMMA                          211   8     46    211   8     46    ,
WHITESPACE                     212   8     47    212   8     47     
IDENTIFIER                     213   8     48    213   8     48    c
WHITESPACE                     214   8     49    214   8     49     
EXPRESSION_ARROW               215   8     50    216   8     51    =>
WHITESPACE                     217   8     52    217   8     52     
IDENTIFIER                     218   8     53    218   8     53    c
WHITESPACE                     219   8     54    219   8     54     
EXPRESSION_OPERATOR_ADD        220   8     55    220   8     55    +
WHITESPACE                     221   8     56    221   8     56     
NUMBER_INTEGER                 222   8     57    222   8     57    2
BRACKET_ROUND_CLOSE            223   8     58    223   8     58    )
EXPRESSION_END                 224   8     59    224   8     59    }
END_OF_FILE                    185   8     20    185   8     20    
';
