#include <iostream>
#include <fstream>
#include <vector>

#define MAXN 1005
#define MAXM 1005

using namespace std;

int N, M;
char c;
vector<pair<int,int>> adj[MAXN][MAXM];
int visited[MAXN][MAXM];

bool is_out(int x, int y){
  if( x >= 0 && x <= N-1  && y >= 0 && y <= M-1 ) return false;
  return true;
}

void add_edge(int x1, int y1, int x2, int y2){

  if(is_out(x2,y2)){
    x2 = N;
    y2 = M;
  }

  // we save the edge reversed
  adj[x2][y2].push_back({x1,y1});
}

void dfs(int x, int y){
  visited[x][y] = 1;


  for(int i = 0; i < (int) adj[x][y].size(); i++){

    int xNext = adj[x][y][i].first;
    int yNext = adj[x][y][i].second;

    if( !visited[xNext][yNext] ){
      dfs(xNext, yNext);
    }
  }
}

int main(int argc, char* argv[]){
  ifstream inputFile;
  inputFile.open(argv[1]);

  inputFile >> N >> M;

  for(int i = 0; i < N; i++){
    for(int j = 0; j < M; j++){
      inputFile >> c;
      switch (c) {
        case 'L':
          add_edge(i,j,i,j-1);
          break;
        case 'R':
          add_edge(i,j,i,j+1);
          break;
        case 'D':
          add_edge(i,j,i+1,j);
          break;
        case 'U':
          add_edge(i,j,i-1,j);
          break;
      }
    }
  }

  visited[N][M] = 1;

  dfs(N,M);

  int result = 0;

  for(int i = 0; i < N; i++){
    for(int j = 0; j < M; j++){
      if(visited[i][j] == 0){
        result++;
      }
    }
  }

  cout << result << endl;

}