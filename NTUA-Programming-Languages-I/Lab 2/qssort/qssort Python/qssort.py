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

        if len(self.queue) > 0:
            # Q Move -- remove from queue add to stack
            q_stack = copy(self.stack)
            q_queue = copy(self.queue)
            x = q_queue.pop(0)
            q_stack.append(x)

            Q_State = State(q_queue, q_stack)

            nextStates.append(('Q', Q_State))

        if len(self.stack) > 0:
            # S Move -- remove from stack add to queue
            s_stack = copy(self.stack)
            s_queue = copy(self.queue)

            x = s_stack.pop()
            s_queue.append(x)

            S_State = State(s_queue, s_stack)

            nextStates.append(('S', S_State))

        return nextStates

    def isFinal(self):
        return self.queue == sorted(self.queue) and len(self.stack) == 0

visited = set()
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
    visited.add(s)
    father[s] = s
    operation[s] = 'A'

    Q = deque([s])

    while(len(Q) != 0):
        v = Q.popleft()

        if v.isFinal():
            return v

        neighbors = v.getNextStates()

        for t in neighbors:
            op, u = t

            if u not in visited:
                father[u] = v
                operation[u] = op
                visited.add(u)

                Q.append(u)


if __name__ == '__main__':
    # read input
    file = sys.argv[1]
    f = open(file,"r")
    s = f.read()
    lines = s.split('\n')[:-1]

    N = int(lines[0])
    arr = [ int(s) for s in lines[1].split(' ') ]

    initialState = State(arr, [])

    final = BFS(initialState)

    # print(final)
    # print(final.isFinal())

    answer = extractPath(final) if not initialState.isFinal() else "empty"
    print(answer)
