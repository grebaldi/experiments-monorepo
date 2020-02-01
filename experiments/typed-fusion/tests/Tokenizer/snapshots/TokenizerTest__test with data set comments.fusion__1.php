<?php return '
TYPE                           S_IDX S_ROW S_COL E_IDX E_ROW E_COL VALUE
COMMENT_START                  0     0     0     0     0     0     #
COMMENT_CONTENT                1     0     1     20    0     20     Single Line Comment
END_OF_LINE                    21    0     21    21    0     21     
COMMENT_START                  22    1     0     23    1     1     //
COMMENT_CONTENT                24    1     2     51    1     29     Another Single Line Comment
END_OF_LINE                    52    1     30    52    1     30     
COMMENT_START                  53    2     0     54    2     1     /*
COMMENT_CONTENT                55    2     2     78    3     22         Multi Line Comment 
COMMENT_END                    79    4     0     80    4     1     */
END_OF_LINE                    81    4     2     81    4     2      
COMMENT_START                  82    5     0     82    5     0     #
END_OF_LINE                    83    5     1     83    5     1      
COMMENT_CONTENT                84    6     0     85    6     1     //
END_OF_LINE                    86    6     2     86    6     2      
COMMENT_START                  87    7     0     88    7     1     /*
COMMENT_END                    89    7     2     90    7     3     */
END_OF_FILE                    87    7     0     87    7     0     
';
