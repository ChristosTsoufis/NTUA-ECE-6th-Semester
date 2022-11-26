DATA_SEG SEGMENT  
        COMMA DB ', $' 
        MSG0 DB 'AVERAGE=$'
        MSG1 DB 0AH,0DH,'MAX=$'  
        MSG2 DB 'MIN=$'
        N EQU 256 
        FIRST EQU 254
        LOOP_COUNT EQU 255    
        NUMBERS DB N DUP(?) 
        SUM DW 0 
        EVEN_NUMS DW 0
        INPUT DW ? 
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

;----------------------------------------------------------------;
;                           MAIN                                 ;
;----------------------------------------------------------------;
MAIN PROC        
        MOV AX,DATA_SEG
        MOV DS,AX 
        MOV CX,N 
        LEA BX,NUMBERS   
        MOV AL,FIRST
INITIALISE_DATA:                
        MOV [BX],AL 
        INC BX  
        DEC AL
        LOOP INITIALISE_DATA            

;Calculates and outputs average of even data
PRINT_AVG:
        MOV CX,N
        LEA BX,NUMBERS  
LOOP_SUM:   
        MOV DH,0
        MOV DL,[BX]
CHECK_EVEN:
        RCR DX,1 
        JC NOT_EVEN 
IS_EVEN:
        RCL DX,1   
        ADD SUM,DX 
        ADD EVEN_NUMS,1 
        JMP CONTINUE
NOT_EVEN:
        RCL DX,1
CONTINUE:
        INC BX
        LOOP LOOP_SUM
CALC_AVG:  
        PRINT_STR MSG0        
        MOV DX,0
        MOV AX,SUM  
        MOV BX,EVEN_NUMS
        DIV BX     
        MOV INPUT,AX 
        ;MOV X,16 
        CALL PRINT_BIN_TO_HEX 

;Calculates and outputs min and max of data       
PRINT_MIN_MAX:
        MOV CX,N
        LEA BX,NUMBERS  
        MOV DH,[BX]         ;DH holds the maximum
        MOV DL,[BX]         ;DL holds the minimum
CALC_MIN_MAX:
        MOV AL,[BX] 
CHECK_MAX:
        CMP AL,DH 
        JB CHECK_MIN
        MOV DH,AL  
CHECK_MIN:
        CMP AL,DL
        JA END_MIN_MAX
        MOV DL,AL
END_MIN_MAX:
        INC BX
        LOOP CALC_MIN_MAX 
        MOV BH,0
        MOV BL,DH
        MOV INPUT,BX  
        PRINT_STR MSG1
        CALL PRINT_DEC
        PRINT_STR COMMA 
        MOV BH,0
        MOV BL,DL
        MOV INPUT,DX
        PRINT_STR MSG2
        CALL PRINT_DEC
        
QUIT:  
        EXIT
MAIN ENDP
CODE_SEG ENDS
END MAIN
        
            
