#include <iostream>
#include <fstream>
#include <algorithm>

#define MAXN 500005

using namespace std;

int N, M;
int a[MAXN], prefixSums[MAXN];
int suffixMax[MAXN];

void print_array(int c[]){
  for(int i = 0; i < M; i++){ 
  }
}

int main(int argc, char* argv[]) {

  ifstream input;
  input.open(argv[1]);

  int result = 0;

  input >> M >> N;
  for(int i = 0; i < M; i++){
    int temp;
    input >> temp;
    a[i] = -temp-N;
  }

  print_array(a);

  prefixSums[0] = a[0];
  for(int i = 1; i < M; i++){
    prefixSums[i] = prefixSums[i-1] + a[i];
  }

  print_array(prefixSums);

  suffixMax[M-1] = prefixSums[M-1];
  for(int i = M-2; i >= 0; i--){
    suffixMax[i] = max(suffixMax[i+1], prefixSums[i]);
  }

  print_array(suffixMax);

  for (int i=0;i<M;i++){
    if(prefixSums[i] >= 0 && result < i+1){
      result = i + 1;
    }

    int start = i+1, end = M-1, mid = (start+end)/2;
    int farthest = -1;

    while(start <= end){
      mid = (start+end)/2;

      if(prefixSums[i] <= suffixMax[mid]){
        farthest = mid;
        start = mid+1;
      }
      else {
        end = mid-1;
      }
    }

    if(farthest > 0 && result < farthest - i){
      result = farthest - i;
    }
  }

  cout<<result<<endl;

}
