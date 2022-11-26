#include <avr/io.h>

char a, b, c, d, f0, f1, F;

int main(void){
	DDRB = 0x00;
	DDRA = 0xFF;
	//PORTB = 0x04;  //Random number to check result

	while(1){
		a = PINB;   // Read from gate PINB
		b = a >> 1;
		c = b >> 1; // right shift
		d = c >> 1;
		a = a & 0x01;  // Isolate the 1st LSB
		b = b & 0x01;
		c = c & 0x01;
		d = d & 0x01;
		f0 = ~(((a&(~b))&0x01) | ((b&(~c)&d)&0x01))&0x01;
		f1 = ((a|c)&(b|d)) << 1;  //shift left so that it is saved in PORTA(1)
		F = f0|f1;
		PORTA = F;
	}
	return 0;
}
