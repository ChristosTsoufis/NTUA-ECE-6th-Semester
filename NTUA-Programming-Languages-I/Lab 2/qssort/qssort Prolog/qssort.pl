% Predicates that read the input
% https://courses.softlab.ntua.gr/pl1/2019a/Exercises/read_colors_SWI.pl

read_input(File, Q) :-
    open(File, read, Stream),
    read_line(Stream, _),
    read_line(Stream, Q).

read_line(Stream, L) :-
    read_line_to_codes(Stream, Line),
    atom_codes(Atom, Line),
    atomic_list_concat(Atoms, ' ', Atom),
    maplist(atom_number, Atoms, L).

create_state(Q, S, State) :-
  State = (Q,S).

push_stack([], X, NewStack) :-
  NewStack = [X].

push_stack(Stack, X, NewStack) :-
  NewStack = [X|Stack].

push_queue(Queue, X, NewQueue) :-
  append(Queue, [X], NewQueue).

pop([H|L], PoppedElement, NewQueue) :-
  PoppedElement = H,
  NewQueue = L.

is_sorted([]).
is_sorted([_]).

is_sorted([H1,H2|L]) :-
  H1 =< H2,
  is_sorted([H2|L]),
  !.


pop_all_from_queue(X, [Y|Q], Stack, NewQ, NewStack, Moves, Result) :-
  X \= Y,
  NewQ = [Y|Q],
  NewStack = Stack,
  Result = Moves.

pop_all_from_queue(_, [], Stack, NewQ, NewStack, Moves, Result) :-
  NewQ = [],
  NewStack = Stack,
  Result = Moves.

pop_all_from_queue(X, [X|Qs], Stack, NewQ, NewStack, Moves, Result) :-
  push_stack(Stack, X, IntermediateStack),
  pop_all_from_queue(X, Qs, IntermediateStack, NewQ, NewStack, ['Q' | Moves], Result).

pop_all_from_stack(X, [Y|S], Queue, NewQ, NewStack, Moves, Result) :-
  X \= Y,
  NewQ = Queue,
  NewStack = [Y|S],
  Result = Moves.

pop_all_from_stack(_, [], Queue, NewQ, NewStack, Moves, Result) :-
  NewQ = Queue,
  NewStack = [],
  Result = Moves.

pop_all_from_stack(X, [X|S], Queue, NewQ, NewStack, Moves, Result) :-
  push_queue(Queue, X, IntermediateQueue), !,
  pop_all_from_stack(X, S, IntermediateQueue, NewQ, NewStack, ['S' | Moves], Result).


get_states((Q,S), NextStates) :-
  length(Q,LenQ),
  length(S,LenS),
  ( LenQ > 0 ->
    pop(Q, X, Q_queue),
    push_stack(S, X, Q_stack),
    pop_all_from_queue(X, Q_queue, Q_stack, Q_new_queue, Q_new_stack, [], Q_moves),
    append(['Q'], Q_moves, Q_all_moves),
    atomic_list_concat(Q_all_moves, Q_move_str),
    NextStatesPartialQ = [(Q_move_str, (Q_new_queue, Q_new_stack))]
  ;
    NextStatesPartialQ = []
  ),
  ( LenS > 0 ->
    pop(S, Y, S_stack),
    push_queue(Q, Y, S_queue),
    pop_all_from_stack(Y, S_stack, S_queue, S_new_queue, S_new_stack, [], S_moves),
    append(['S'], S_moves, S_all_moves),
    atomic_list_concat(S_all_moves, S_move_str),
    append(NextStatesPartialQ, [(S_move_str, (S_new_queue, S_new_stack))], NextStatesPartialS)
  ;
    NextStatesPartialS = NextStatesPartialQ
  ),
  NextStates = NextStatesPartialS.

is_final_state((Q,S)) :-
  is_sorted(Q),
  S = [].

% FIFO Queue implementation from the web
fifo_empty(Q) :-
  empty_assoc(X),
  Q = (-1,-1,X).

% fifo_insert(Q1,X,Q2)
% Makes a new FIFO Queue Q2, which is Q1 with X inserted
fifo_insert((-1,-1,A),X,Q2) :-
  put_assoc(0,A,X,NewTree),
  Q2 = (0,1,NewTree),
  !.

fifo_insert((Head,Tail,T),X,Q2) :-
  NewTail is Tail + 1,
  put_assoc(Tail,T,X,NewT),
  Q2 = (Head,NewTail,NewT),
  !.

% fifo_insertList(Q1,L,Q2)
fifo_insertList(Q1,[],Q2) :-
  Q2 = Q1.

fifo_insertList(Q1,[H|L],Q2) :-
  fifo_insert(Q1,H,Q_),
  !,
  fifo_insertList(Q_,L,Q2).

% fifo_pop(Q1,X,Q2)
% Pops an item from Q1.
% X is the item popped and Q2 is the remaining queue
% Returns false if Q1 is empty
fifo_pop((Head,Tail,T),X,Q2) :-
  Head =\= Tail,
  del_assoc(Head,T,X,NewT),
  NewHead is Head + 1,
  Q2 = (NewHead,Tail,NewT),
  !.

% fifo_isEmpty(Q)
% Is true if Q is empty
fifo_isEmpty((Head,Tail,_)) :-
  Head =:= Tail.


% Parent association list -- dictionary
% parent[u] -> ('Q', father[u]) ('S', ...)
% parent[initialState] -> ('A', initialState)

bfs(InitialState, Answer) :-
  (
  is_final_state(InitialState) ->
    Answer ="empty"
    ;
    empty_assoc(X),
    put_assoc(InitialState, X, ('A', InitialState), Parent),
    fifo_empty(Q),
    fifo_insert(Q, InitialState, Q1),
    bfsLoop(Q1, Parent, Answer)
  ).

nodeNotVisited((Q,S),Parent) :-
  \+ get_assoc((Q,S),Parent,_).

add_neighbors(Parent, [], _, BFSq, NewParent, NewQ) :-
  NewParent = Parent,
  NewQ = BFSq.

add_neighbors(Parent, [(M,Q,S)|L], ParentNode, BFSq, NewParent, NewQ) :-
  (
    nodeNotVisited((Q,S),Parent) ->
      put_assoc((Q,S), Parent, (M,ParentNode), Parent_),
      fifo_insert(BFSq, (Q,S), NewBFSq)
      ;
      Parent_ = Parent,
      NewBFSq = BFSq
  ),
  add_neighbors(Parent_, L, ParentNode, NewBFSq, NewParent, NewQ).

checkForAnswer([], Node, Answer) :-
  Answer = 0,
  Node = ([],[]).

checkForAnswer([(_,Q,S)|L], Node, Answer) :-
  (
    is_final_state((Q,S)) ->
      Node = (Q,S),
      Answer = 1
      ;
      checkForAnswer(L, Node, Answer)
  ).

findPath((Q,S), Parent, Acc, Answer) :-
  % writeln('here'),
  % writeln((Q,S)),
  get_assoc((Q,S), Parent, (Move, QP, SP)),
  % writeln((Move, QP, SP)),
  (
    Move = 'A' ->
      atomic_list_concat(Acc,Answer)
      ;
      findPath((QP,SP), Parent, [Move|Acc], Answer)
  ).


bfsLoop(Q, Parent, Answer) :-
  fifo_pop(Q, S, Q_),
  !,
  get_states(S, Neighbors),
  !,
  % writeln(S),
  add_neighbors(Parent, Neighbors, S, Q_, NewParent, NewQ),
  !,
  % writeln('1'),
  checkForAnswer(Neighbors, Node, FoundAnswer),
  !,
  % writeln('2'),
  (
    FoundAnswer =:= 1 ->
      % writeln('here'),
      findPath(Node, NewParent, [], Answer)
      ;
      % writeln('2'),
      bfsLoop(NewQ, NewParent, Answer)
  ).

% InitialState = ([7,17,3,42],[20]),
% empty_assoc(X),
% fifo_empty(Q),
% get_states(InitialState, Neighbors),
% add_neighbors(X, Neighbors, InitialState, Q, NewParent, NewQ).

qssort(File, Answer) :-
  read_input(File, Q),
  bfs((Q,[]), Answer).
