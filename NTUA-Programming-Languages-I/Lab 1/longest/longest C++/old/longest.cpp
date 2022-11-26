#include <iostream>
#include <fstream>
using namespace std;

int sum(int a[],int begin,int finish){
    int sum = 0;
    for(int i = begin; i <= finish; i++)
        sum += a[i];
    return sum;
}

int main () {
    int result = 0;
    int sum1;
    
    int meres,N;
    int a[500000];
    int idx = -2;
    ifstream inputfile;
    inputfile.open(argv[1]);
    while(!inputfile.eof()){
        if (idx==-2) {
            inputfile >> meres; 
        }
        else if (idx==-1){
            inputfile >> N;
        }
        else {
        inputfile >> a[idx];
        }
        idx++; 
    }

        for (int i=0;i<meres;i++){
        for(int j=i;j<meres;j++){
            int curr_length = j - i + 1;
            sum1 = sum(a,i,j);
            int help = abs(sum1)/((curr_length)*(N));
            if(sum1<0 && help>=1 && curr_length > result  ){
                result = curr_length; 
            }
            else {
                continue;
            }
        }
    }
    cout<<result<<endl;
    
}