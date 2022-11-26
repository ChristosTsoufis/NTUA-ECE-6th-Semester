% Predicates that read the input
% https://courses.softlab.ntua.gr/pl1/2019a/Exercises/read_colors_SWI.pl

read_input(File, M, N, H) :-
    open(File, read, Stream),
    read_line(Stream, [M, N]),
    read_line(Stream, H).

read_line(Stream, L) :-
    read_line_to_codes(Stream, Line),
    atom_codes(Atom, Line),
    atomic_list_concat(Atoms, ' ', Atom),
    maplist(atom_number, Atoms, L).

prefixSum([], _, PartialResult, Result) :-
  reverse(PartialResult, Result).

prefixSum([X|Xs], RunningSum, PartialResult, Result) :-
  NewRunningSum is RunningSum + X,
  NewResult = [NewRunningSum | PartialResult], !,
  prefixSum(Xs, NewRunningSum, NewResult, Result).

max(X,Y,M):-
    X>Y,!,
    M=X.
max(X,Y,M):-
    X=<Y,
    M=Y.

firstElem([X|_], Head) :-
  Head = X.

maxFromStart([], _, PartialResult, Result) :-
  PartialResult = Result.

maxFromStart([X|Xs], RunningMax, PartialResult, Result) :-
  max(RunningMax,X,NewRunningMax),
  NewResult = [NewRunningMax | PartialResult], !,
  maxFromStart(Xs, NewRunningMax, NewResult, Result).

maxFromEnd([], []).
maxFromEnd([X|Xs], Y) :-
  reverse([X|Xs], ReversedX),
  firstElem(ReversedX, X0), !,
  maxFromStart(ReversedX, X0, [], Y).

insertListToTree([], RunningTree, _, ResultTree) :-
  ResultTree = RunningTree.

insertListToTree([X|Xs], RunningTree, Index, ResultTree) :-
  put_assoc(Index, RunningTree, X, NewTree),
  NewIndex is Index + 1, !,
  insertListToTree(Xs, NewTree, NewIndex, ResultTree).

binarySearch(Start, End, _, _, Farthest, Result) :-
  Start > End,
  Result = Farthest.
  % writeln("done").

binarySearch(Start, End, Pi, Tree, Farthest, Result) :-
  % writeln(Start), % writeln(End),
  Mid is div(Start+End,2),
  % writeln(Mid),
  get_assoc(Mid, Tree, MaxMid),
  % writeln('hereee'),
  (
    Pi > MaxMid ->
      % writeln('here1'),
      NewStart = Start,
      NewFarthest = Farthest,
      NewEnd is Mid - 1
      ;
      % writeln('here2'),
      NewStart is Mid + 1,
      NewFarthest is max(Farthest, Mid),
      NewEnd = End
  ),
  !,
  % writeln('before recursion'),
  binarySearch(NewStart, NewEnd, Pi, Tree, NewFarthest, Result).

and(X,Y) :-
  X, Y.

loop([], _, RunningAnswer, _, _, Result) :-
  Result = RunningAnswer.
  % writeln(RunningAnswer).

loop([Pi | Ps], I, RunningAnswer, M, Tree, Result) :-
  % writeln(I),
  (
   and(Pi >= 0, RunningAnswer < I+1) ->
     RunningAnswer1 is I+1
     % writeln('here')
     ;
     RunningAnswer1 is RunningAnswer
  ),
  Start is I+1,
  End is M-1, !,
  binarySearch(Start, End, Pi, Tree, -1, Farthest),
  % writeln('after bs'),
  (
    and(Farthest > 0, RunningAnswer1 < (Farthest - I)) ->
      RunningAnswer2 is Farthest - I
      ;
      RunningAnswer2 is RunningAnswer1
  ),
  !,
  NewIndex is I+1, !,
  loop(Ps, NewIndex, RunningAnswer2, M, Tree, Result).

transform([], _, PartialResult, Result) :-
  reverse(PartialResult,Result).

transform([X|Xs], N, PartialResult, Result) :-
  NewX is -X-N,
  NewPartialResult = [NewX | PartialResult], !,
  transform(Xs, N, NewPartialResult, Result).

longest(File, Answer) :-
  read_input(File, M, N, Array),
  transform(Array, N, [], A),
  prefixSum(A, 0, [], PrefixSum), !,
  % writeln(PrefixSum),
  maxFromEnd(PrefixSum, MaxFromEnd), !,
  % writeln(MaxFromEnd),
  empty_assoc(Y),
  insertListToTree(MaxFromEnd, Y, 0, T), !,
  loop(PrefixSum, 0, 0, M, T, Answer).
  % writeln(Answer).
