DATA_SEG SEGMENT 
        X_NUM DB ?
        Y_NUM DB ? 
        INPUT DW ?
        MSG1 DB 'x=$'
        MSG2 DB 'y=$'
        SPACE DB ' $' 
        NEWLINE DB 0AH,0DH,'$'
        MINUS DB '-$'
        MSG3 DB 'x+y=$'
        MSG4 DB 'x-y=$'         
DATA_SEG ENDS

CLEAR MACRO
        MOV AH,0
        int 10H
ENDM

EXIT MACRO 
        MOV AX, 4C00H 
        INT 21H
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

;READS ONE HEX DIGIT       
HEX_KEYB PROC NEAR
        PUSH DX 
IGNORE:
        READ                     
        CMP AL,30H  
        JL IGNORE
        CMP AL,39H
        JG A_F
        PUSH AX
        PRINT AL
        POP AX
        SUB AL,30H
        JMP END_HEX_KEYB
A_F:
        CMP AL,'A'
        JL IGNORE
        CMP AL,'F'
        JG IGNORE
        PUSH AX 
        PRINT AL
        POP AX
        SUB AL,37H
END_HEX_KEYB:
        POP DX
        RET
HEX_KEYB ENDP

;Reads 2 digit hex number and returns it in DX
READ_HEX_NUM PROC
READ_INPUT: 
        MOV DX,0
        MOV CX,2
LOOP_INPUT:
        CALL HEX_KEYB 
        SHL DX,1
        SHL DX,1
        SHL DX,1
        SHL DX,1
        ADD DL,AL
        LOOP LOOP_INPUT
        MOV BX,DX
        MOV DX,BX
        MOV CX,0 
        
        RET
READ_HEX_NUM ENDP

;Prints 4-bits in DL as one hex digit
PRINT_HEX PROC
        CMP DL,9
        JG IS_DIGIT
        ADD DL,30H
        JMP PRINT_HEX_CHAR
IS_DIGIT:
        ADD DL,37H
PRINT_HEX_CHAR: 
        PRINT DL
        RET
PRINT_HEX ENDP

;Binary to hexadecimal
PRINT_BIN_TO_HEX PROC
        PUSH DX
        MOV AX,0
        MOV AX,INPUT
        MOV CX,0
LOOP_REVERSE:
        MOV DX,0
        MOV BX,16
        DIV BX
        PUSH DX
        INC CX
        CMP AX,0
        JNE LOOP_REVERSE
LOOP_PRINT_HEX:
        POP DX 
        CALL PRINT_HEX
        LOOP LOOP_PRINT_HEX
         
        POP DX 
        RET
PRINT_BIN_TO_HEX ENDP 

;PRINTS DECIMAL FORM              
PRINT_DEC PROC NEAR 
        PUSH DX 
        MOV AX,DATA_SEG
        MOV DS,AX   
        MOV AX,INPUT  
        MOV CX,0        ;CX stores the number of digits
ADDR4: 
        MOV DX,0
        MOV BX,10
        DIV BX
        PUSH DX 
        INC CX          ;CX is increased for every new digit
        CMP AX,0
        JNE ADDR4    
ADDR5:
        POP DX
        ADD DX,30H
        PRINT DL
        LOOP ADDR5
        
        POP DX
        RET
PRINT_DEC ENDP

MAIN PROC 
        MOV AX,DATA_SEG
        MOV DS,AX 
        CALL READ_HEX_NUM
        MOV X_NUM,DL      
        PRINT_STR SPACE
        CALL READ_HEX_NUM
        MOV Y_NUM,DL   
A_PART:   
        CLEAR
        PRINT_STR MSG1 
        MOV DH,0
        MOV DL,X_NUM
        MOV INPUT,DX 
        CALL PRINT_BIN_TO_HEX 
        PRINT_STR SPACE   
        PRINT_STR MSG2 
        MOV DH,0
        MOV DL,Y_NUM
        MOV INPUT,DX 
        CALL PRINT_BIN_TO_HEX  
        
        PRINT_STR NEWLINE
B_PART:   
        PRINT_STR MSG3
        MOV AH,0
        MOV AL,X_NUM
        MOV BH,0
        MOV BL,Y_NUM
        ADD AX,BX  
        MOV INPUT,AX
        CALL PRINT_DEC
        PRINT_STR SPACE
        PRINT_STR MSG4
        MOV AH,0
        MOV AL,X_NUM
        MOV BH,0
        MOV BL,Y_NUM  
        CMP AX,BX
        JL  NEGATIVE
POSITIVE:
        SUB AX,BX
        MOV INPUT,AX
        JMP FINISH
NEGATIVE:         
        SUB BX,AX
        MOV INPUT,BX 
        PRINT_STR MINUS
        JMP FINISH
FINISH:       
        CALL PRINT_DEC
        
        EXIT       
MAIN ENDP
CODE_SEG ENDS  
END MAIN