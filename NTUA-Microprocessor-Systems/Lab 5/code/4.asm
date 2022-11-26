DATA_SEG SEGMENT
        TEXT DB 16 DUP(?)
        NEWLINE DB 0AH,0DH,'$'
DATA_SEG ENDS 

EXIT MACRO 
        MOV AX, 4C00H 
        INT 21H
ENDM

CLEAR MACRO
        MOV AH,0
        int 10H
ENDM             

READ MACRO 
        MOV AH, 8
        INT 21H 
ENDM

PRINT MACRO CHAR
        MOV DL,CHAR
        MOV AH,2
        INT 21H
ENDM

PRINT_STR MACRO STRING          
        MOV DX,OFFSET STRING    
        MOV AH,9
        INT 21H
ENDM         

CODE_SEG SEGMENT
        ASSUME CS:CODE_SEG, DS:DATA_SEG

MAIN PROC
START: 
        MOV AX,DATA_SEG
        MOV DS,AX         
        MOV CX,16 
        LEA BX,TEXT
READ_INPUT:
        READ
        CMP AL,0DH
        JE QUIT       
        PRINT AL
        MOV [BX],AL             ;Store character in TEXT
        INC BX
        LOOP READ_INPUT 
        PRINT_STR NEWLINE
        
        MOV CX,16
        LEA BX,TEXT
FIND_NUMBERS:
        MOV AL,[BX]
        CMP AL,'0'
        JB CONTINUE_FIND_NUMBERS
        CMP AL,'9'
        JA CONTINUE_FIND_NUMBERS
        PRINT AL 
CONTINUE_FIND_NUMBERS:
        INC BX
        LOOP FIND_NUMBERS
         
PRINT_SEPARATOR:       
        MOV AX,0
        PRINT '-'
        
        MOV CX,16
        LEA BX,TEXT
FIND_LETTERS:
        MOV AL,[BX]
        CMP AL,'A'
        JB CONTINUE_FIND_LETTERS
        CMP AL,'Z'
        JA CONTINUE_FIND_LETTERS
        MOV DL,'a'
        SUB DL,'A'
        ADD AL,DL           ;Turn to lowercase
        PRINT AL
CONTINUE_FIND_LETTERS:
        INC BX
        LOOP FIND_LETTERS 
        PRINT_STR NEWLINE
        JMP START
                
QUIT:
        EXIT      
        
MAIN ENDP
CODE_SEG ENDS 
END MAIN