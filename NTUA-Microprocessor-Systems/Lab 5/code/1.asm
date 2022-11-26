DATA_SEG SEGMENT
        INPUT DW 000H
        EQUAL_CHAR DB ' ',3DH,' $' 
        NEW_LINE DB 0AH,0DH,'$'
DATA_SEG ENDS
  
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
        CMP AL,'Q'              
        JE ADDR2 
        CMP AL,30H  
        JL IGNORE
        CMP AL,39H
        JG ADDR1
        PUSH AX
        PRINT AL
        POP AX
        SUB AL,30H
        JMP ADDR2
ADDR1:
        CMP AL,'A'
        JL IGNORE
        CMP AL,'F'
        JG IGNORE
        PUSH AX 
        PRINT AL
        POP AX
        SUB AL,37H
ADDR2:
        POP DX
        RET
HEX_KEYB ENDP
              
;PRINTS DECIMAL FORM              
PRINT_DEC PROC NEAR  
        MOV AX,DATA_SEG
        MOV DS,AX
        MOV AX,INPUT  
        MOV CX,0        ;CX stores the number of digits of decimal
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

        RET
PRINT_DEC ENDP       

;PRINTS OCTAL FORM
PRINT_OCT PROC NEAR
        MOV AX,DATA_SEG
        MOV DS,AX
        MOV AX,INPUT
        MOV CX,0   
DIV_OCT:     
        MOV DX,0
        MOV BX,8
        DIV BX
        PUSH DX   
        INC CX
        CMP AX,0
        JNE DIV_OCT       
REV_OCT:
        POP DX
        ADD DX,30H
        PRINT DL
        LOOP REV_OCT 
        
        RET
PRINT_OCT ENDP   

;PRINTS BINARY FORM
PRINT_BIN PROC NEAR     
        MOV AX,DATA_SEG
        MOV DS,AX
        MOV AX,INPUT
        MOV CX,0
DIV_BIN:     
        MOV DX,0
        MOV BX,2
        DIV BX
        PUSH DX   
        INC CX
        CMP AX,0
        JNE DIV_BIN        
REV_BIN:
        POP DX
        ADD DX,30H
        PRINT DL
        LOOP REV_BIN  
         
        RET
PRINT_BIN ENDP   

;---------------------------------------------------------;
;---------------------------------------------------------;

MAIN PROC FAR         
RESTART:
        MOV AX,DATA_SEG
        MOV DS,AX 
READ_INPUT: 
        MOV DX,0
        MOV CX,2
LOOP_INPUT:
        CALL HEX_KEYB
        CMP AL,'Q'
        JE QUIT  
        SHL DX,1
        SHL DX,1
        SHL DX,1
        SHL DX,1
        ADD DL,AL
        LOOP LOOP_INPUT
        MOV BX,DX
        MOV DX,BX
        MOV CX,0
        MOV INPUT,DX        ;Save the input number    
        

PRINT_DECIMAL: 
        PRINT_STR EQUAL_CHAR
        CALL PRINT_DEC     
        PRINT_STR EQUAL_CHAR
        CALL PRINT_OCT
        PRINT_STR EQUAL_CHAR
        CALL PRINT_BIN   
              
        PRINT_STR NEW_LINE
        JMP RESTART
        
QUIT:
        EXIT       

        
MAIN ENDP
CODE_SEG ENDS
END MAIN
                


            