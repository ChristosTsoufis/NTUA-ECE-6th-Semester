DATA_SEG SEGMENT
        GIVE_INPUT DB 'A/D OUTPUT:$' 
        TEMPERATURE_IS DB 'TEMPERATURE IS:$'  
        ERROR DB 'ERROR$'
        MSG DB 'START (Y, N):$'
        NEWLINE DB 0AH,0DH,'$'
        AD_OUT DW ? 
        TEN DW 10
        DIVISOR DW 4095
        A1 DW 1000 
        B1 DW 0
        A2 DW 500
        B2 DW 250
        A3 DW 3000
        B3 DW -2000          
        A DW ?
        B DW ?
        AREA1 DW 2047
        AREA2 DW 3685
        AREA3 DW 4095 
        TEMP DW ?
        ROUND_DIGIT DW ?
        REMAINDER DW ?
        INPUT DW ?
DATA_SEG ENDS

PARAMETERS MACRO P1,P2
        MOV AX,P1
        MOV A,AX
        MOV AX,P2
        MOV B,AX
ENDM

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
        PUSH DX 
        MOV DL,CHAR 
        MOV AH,2 
        INT 21H
        POP DX
ENDM
 
PRINT_STR MACRO STRING          
        MOV DX,OFFSET STRING    
        MOV AH,9
        INT 21H
ENDM  

CODE_SEG SEGMENT
        ASSUME DS:DATA_SEG,CS:CODE_SEG 
        
ROUND PROC
        MOV ROUND_DIGIT,0
        MOV AX,DIVISOR
        SAR AX,1
        CMP REMAINDER,AX    ;IF REMAINDER > (DIVIDER/2) ROUND UP!
        JL ROUND_EXIT
        MOV ROUND_DIGIT,1              
ROUND_EXIT:
        MOV AX,TEMP
        ADD AX,ROUND_DIGIT 
        
        RET
ROUND ENDP

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
        CMP CX,1
        JNE JUST_A_DIGIT
        PRINT '.'
JUST_A_DIGIT:
        PRINT DL
        LOOP ADDR5
QUIT_PRINT_DEC:
        RET
PRINT_DEC ENDP

;READS ONE HEX DIGIT       
HEX_KEYB PROC NEAR
PUSH DX 
IGNORE:
        READ
        CMP AL,'N'
        JE QUIT                    
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

;SAVES THE TEMPERATURE IN TEMP  
CALC_TEMPERATURE PROC 
        MOV DX,0
        MOV AX,AD_OUT
        MUL TEN
        MUL A 
        DIV DIVISOR
        MOV TEMP,AX 
        MOV REMAINDER,DX
        CALL ROUND 
        MOV TEMP,AX
        MOV AX,B
        MUL TEN
        ADD TEMP,AX 
        
        RET
CALC_TEMPERATURE ENDP      

PROC MAIN  
        MOV AX,DATA_SEG
        MOV DS,AX 
START:  
        PRINT_STR MSG
        READ 
        PRINT AL
        CMP AL,'Y'
        JE READ_INPUT
        CMP AL,'N'
        JE QUIT
        PRINT_STR NEWLINE
        JMP START
               
READ_INPUT:
        PRINT_STR NEWLINE
        PRINT_STR GIVE_INPUT
        MOV CX,3
        MOV DX,0
LOOP_READ:
        CALL HEX_KEYB 
        SHL DX,4
        ADD DL,AL
        LOOP LOOP_READ
        MOV CX,0
        MOV AX,DX
        MOV AD_OUT,AX 

CHECK_INPUT:   
        CMP AX,0
        JL QUIT
        CMP AX,AREA1
        JLE CHOOSE_AREA1
        CMP AX,AREA2
        JLE CHOOSE_AREA2
        CMP AX,AREA3
        JLE CHOOSE_AREA3
   
CHOOSE_AREA1:
        PARAMETERS A1,B1
        JMP PRINT_NUM 
        
CHOOSE_AREA2:
        PARAMETERS A2,B2
        JMP PRINT_NUM
   
CHOOSE_AREA3:
        PARAMETERS A3,B3
        JMP PRINT_NUM
           
PRINT_NUM:
        CALL CALC_TEMPERATURE
        MOV AX,TEMP
        MOV INPUT,AX
        PRINT_STR NEWLINE
        PRINT_STR TEMPERATURE_IS
        CMP INPUT,9999
        JLE CONTINUE_PRINT_NUM
        PRINT_STR ERROR
        JMP START
CONTINUE_PRINT_NUM:
        CALL PRINT_DEC
         
        PRINT_STR NEWLINE
        PRINT_STR NEWLINE
        JMP START

QUIT:
        EXIT              
MAIN ENDP
CODE_SEG ENDS
END MAIN
        