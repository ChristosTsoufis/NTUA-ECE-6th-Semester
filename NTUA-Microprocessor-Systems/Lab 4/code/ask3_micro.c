#include <avr/io.h>
char button_pressed, x;

int main(void)
{
	DDRB = 0xFF;
	DDRA = 0x00;
	PORTB = 0x01;
	
	while (1)
	{
		
		button_pressed = PINA;   //PINA.. works fine with both
		if (button_pressed == 1){
			PORTB = PORTB >> 1; // RIGHT SHIFT
			if (PORTB == 0){
				PORTB = 0x80;
			}
		}
		else if (button_pressed == 2){
			PORTB = PORTB << 1;  //left shift
			if (PORTB == 128){
				PORTB = 0x01;
			}
		}
		else if (button_pressed == 4){
			PORTB = 0x01; //Turn LSB of PORTB ON
		}
		else if (button_pressed == 8){
			PORTB = 0x80;  //Turn MSB of PORTB ON
		}
	}
	return 0;
}
