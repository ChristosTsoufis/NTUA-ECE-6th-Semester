read_input(File, T, C, Cars) :-
    open(File, read, Stream),
    read_line(Stream, [T, C]),
    read_line(Stream, Cars).


read_line(Stream, L) :-
    read_line_to_codes(Stream, Line),
    atom_codes(Atom, Line),
    atomic_list_concat(Atoms, ' ', Atom),
    maplist(atom_number, Atoms, L).


diafora(_, _, [], Diaf, Answer) :-
    Answer = Diaf,
    !.

diafora(I, T, [H2|T2], Diaf, Answer) :-
    (I < H2 ->
        diafora(I, T, T2, [T+I-H2|Diaf], Answer),
        !
        ; 
        diafora(I, T, T2, [I-H2|Diaf], Answer),
        !
    ).
    

townList([], [H], _, Acc) :-
    H is Acc.

townList([H|T], [H1|T1], Current, Acc) :-
    (H =:= Current -> NewAcc is Acc+1, 
    townList(T, [H1|T1], Current, NewAcc)
    ;
    H1 is Acc, 
    townList(T, T1, H, 1)
    ).


funct([], [], I, ResList, Result, LastI) :-
    Result = ResList,
    LastI is I.

funct([Hc|Tc], [Ht|Tt], I, ResList, Result, LastI) :-
    NewI is I+1,
    (Hc =:= I ->
        funct(Tc, Tt, NewI, [Ht|ResList], Result, LastI)
    ;
    funct([Hc|Tc], [Ht|Tt], NewI, [0|ResList], Result, LastI)
    ).


fill_zeros(I, T, ResList, Result) :-
    (I < T ->
        NewI is I+1,
        fill_zeros(NewI, T, [0|ResList], Result),
        !
        ;
        reverse(ResList, Result),
        !
    ).


find_next(_, T, [], Every_T, Result) :-
    find_next(0, T, Every_T, Every_T, Result),
    !.

find_next(I, T, [H1|T1], Every_T, Result) :-
    NewI is I+1,
    (H1 = 0 ->
        find_next(NewI, T, T1, Every_T, Result),
        !
        ;
        Result = I,
        !
    ).

get_elements((X, Y), First, Second) :-
    First = X,
    Second = Y.


solution(_, _, _, [], _, _, _, (Min, Y), _, Result) :-
    Result = (Min, Y),
    !.

solution(I, C, T, [H1|T1], Sum, Max, MaxCity, (Min, Y), Cities, Result) :-
    NewI is I + 1,
    NewSum is Sum + C - H1*T,
    (not(MaxCity = I) ->
        NewMaxCity = MaxCity,
        NewMax is Max+1
        ; 
        find_next(NewI, T, T1, Cities, TempMax),
    
    (I-TempMax < 0 ->
        NewMax is T + I - TempMax
        ;
        NewMax is I - TempMax
    ),  
    
    NewMaxCity = TempMax
    ),
    (NewSum < Min ->
        (NewSum + 1 > 2*NewMax ->
            solution(NewI, C, T, T1, NewSum, NewMax, NewMaxCity, (NewSum, I), Cities, Result)
            ;
            solution(NewI, C, T, T1, NewSum, NewMax, NewMaxCity, (Min, Y), Cities, Result)
        )
    ;
    solution(NewI, C, T, T1, NewSum, NewMax, NewMaxCity, (Min, Y), Cities, Result),
    !
    ).


round(File, Moves, Town) :-
    read_input(File, T, C, Cars),

    msort(Cars, SortCars),
    [L|_] = SortCars,
    townList(SortCars, A, L, 0),

    sort(Cars, K),
    funct(K, A, 0, [], Res, I),
    fill_zeros(I, T, Res, CinT),
    diafora(0, T, Cars, [], Diaf),

    sumlist(Diaf, Sum),
    max_list(Diaf, Max),
    MaxCity is T-Max,
    [_|T1] = CinT,
    
    solution(1, C, T, T1, Sum, Max, MaxCity, (Sum, 0), CinT, Final),
    get_elements(Final, Moves, Town).