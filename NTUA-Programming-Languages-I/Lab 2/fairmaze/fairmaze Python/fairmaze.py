import sys
import numpy as np
from collections import deque

MAXN = 1005
MAXM = 1005

adj = {}
visited = set()
component = {}

def is_out(x, y):
    if x>=0 and  x <= N-1 and y >= 0 and y <= M-1 :
        return False
    else:
        return True

def add_edge(x1, y1, x2, y2):
    if is_out(x2, y2):
        x2 = N
        y2 = M

    # we save the edge reversed
    if (x2,y2) not in adj:
        adj[(x2,y2)] = []

    adj[(x2,y2)].append((x1,y1))

def bfs(x, y):
    visited.add((x,y))
    Q = deque([(x,y)])

    while(len(Q)!=0):
        v = Q.popleft()

        z = 0 if v not in adj else len(adj[v])

        for i in range(z):
            x = adj[v][i]

            if x not in visited:
                visited.add(x)
                Q.append(x)


file = sys.argv[1]
f = open(file,"r")
s = f.read()
lines = s.split('\n')[:-1]

N, M = [int(x) for x in lines[0].split(' ')]

for i in range(N):
    for j in range(M):

        c = lines[i+1][j]

        if c == 'L':
            add_edge(i,j,i,j-1)
        elif c == 'R':
            add_edge(i,j,i,j+1)
        elif c == 'D':
            add_edge(i,j,i+1,j)
        elif c == 'U':
            add_edge(i,j,i-1,j)

visited.add((N,M))

bfs(N, M)

result = 0

for i in range(N):
    for j in range(M):
        if (i,j) not in visited:
            result += 1

print(result)
