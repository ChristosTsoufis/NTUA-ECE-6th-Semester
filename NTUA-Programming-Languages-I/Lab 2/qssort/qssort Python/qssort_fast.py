import sys
from copy import deepcopy, copy
from collections import deque

class State:

    def __init__(self, queue, stack):
        self.stack = stack
        self.queue = queue

    def __str__(self):
        return f'State with queue: {self.queue} and stack: {self.stack}'


    def getNextStates(self):

        nextStates = []

        if self.queue:
            # Q Move -- remove from queue add to stack
            # q_stack = copy(self.stack)
            # q_queue = copy(self.queue)
            # x = q_queue.pop(0)
            # q_stack.append(x)

            q_queue = list(self.queue[1:])
            q_stack = self.stack + [self.queue[0]]

            Q_State = State(q_queue, q_stack)

            nextStates.append(('Q', Q_State))

        if self.stack:
            # S Move -- remove from stack add to queue
            # s_stack = copy(self.stack)
            # s_queue = copy(self.queue)

            # x = s_stack.pop()
            # s_queue.append(x)

            s_stack = list(self.stack[:-1])
            s_queue = self.queue + [self.stack[-1]]

            S_State = State(s_queue, s_stack)

            nextStates.append(('S', S_State))

        return nextStates

    def isFinal(self):
        return self.queue == sorted(self.queue) and len(self.stack) == 0


def getNextStates(s,i,j):

        queue = s[0]
        stack = s[1]

        nextStates = []

        if i > 1:
            q_queue = queue[i:]
            q_stack = stack + queue[0:i]

            Q_State = (q_queue, q_stack)

            nextStates.append(('Q'*i, Q_State))
        elif s[0]:
            # Q Move -- remove from queue add to stack
            # q_stack = copy(self.stack)
            # q_queue = copy(self.queue)
            # x = q_queue.pop(0)
            # q_stack.append(x)

            q_queue = queue[1:]
            q_stack = stack + (queue[0],)

            Q_State = (q_queue, q_stack)

            nextStates.append(('Q', Q_State))

        if j > 1:
            s_stack = stack[:-j]
            s_queue = queue + (stack[j:-1],)

            S_State = (s_queue, s_stack)

            nextStates.append(('S', S_State))
        elif s[1]:
            # S Move -- remove from stack add to queue
            # s_stack = copy(self.stack)
            # s_queue = copy(self.queue)

            # x = s_stack.pop()
            # s_queue.append(x)

            s_stack = stack[:-1]
            s_queue = queue + (stack[-1],)

            S_State = (s_queue, s_stack)

            nextStates.append(('S', S_State))

        return nextStates




# visited = set()
father = {}
operation = {}
# father[s] = s' ---- (s,s' states)
# operation[s] = 'Q' or 'S' <- me ti kinisi pas apo ton father[s] ston s

def extractPath(endNode):
    path = ""

    current = endNode
    previous = father[current]

    while current != previous:
        path += operation[current]
        current = previous
        previous = father[previous]

    return path[::-1]


def BFS(s):
    # visited.add(s)
    # print(type(s), s)
    father[s] = s
    operation[s] = 'A'

    Q = deque([s])

    while Q:
        v = Q.popleft()

        if v[0] == finalState:
            return v

        # neighbors = v.getNextStates()


        i = 0
        while i < len(v[0]) and v[0][i] == v[0][0] : i += 1  #queue
        j = -1
        while j < -len(v[1])-1 and v[1][j] == v[1][-1] : j -= 1  #stack


        neighbors = getNextStates(v,i,j)
        

        # print(neighbors)

        for t in neighbors:
            op, u = t

            if u not in father:
                father[u] = v
                operation[u] = op
                # visited.add(u)

                Q.append(u)


if __name__ == '__main__':
    # read input
    # file = sys.argv[1]
    # f = open(file,"r")
    # s = f.read()
    # lines = s.split('\n')[:-1]

    with open(sys.argv[1], 'r') as file :
        lines = file.readlines()

    N = int(lines[0])

    if N == 0:
        print('empty')
    else:
        L = tuple(map(int,lines[1].split()))

        # N = int(lines[0])
        # arr = [ int(s) for s in lines[1].split(' ') ]

        initialState = (L, ())
        finalState = tuple(sorted(L))

        if initialState[0] == finalState:
            print('empty')
        else:
            final = BFS(initialState)

            # print(final)
            # print(final.isFinal())

            answer = extractPath(final)
            print(answer)