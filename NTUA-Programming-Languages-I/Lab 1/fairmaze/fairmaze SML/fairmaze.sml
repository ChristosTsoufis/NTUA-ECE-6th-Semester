(* The function 'parse' is based on the one publicly available here:
https://courses.softlab.ntua.gr/pl1/2013a/Exercises/countries.sml *)

fun parse file =
    let
  	    (* Open input file. *)
      	val inStream = TextIO.openIn file

        (* A function to read an integer from specified input. *)
        fun readInt input =
  	     Option.valOf (TextIO.scanStream (Int.scan StringCvt.DEC) input)

        (* Reads lines until EOF and puts them in a list as char lists *)
        fun readLines acc =
          let
            val newLineOption = TextIO.inputLine inStream
          in
            if newLineOption = NONE
            then (rev acc)
            else ( readLines ( explode (valOf newLineOption ) :: acc ))
        end;

        val n = readInt inStream
        val m = readInt inStream
        val grid = tl (readLines [])
    in
   	    (n, m, grid)
end;

fun tupleCompare ((x1,y1), (x2,y2)) =
  let
    val firstComp = Int.compare (x1,x2)
    val secondComp = Int.compare (y1,y2)
  in
    if (firstComp = EQUAL)
    then secondComp
    else firstComp
  end;

structure S = BinaryMapFn(
  struct
    type ord_key = int * int
    val compare = tupleCompare
  end
)

fun isOutside (x,y) n m =
  let
    val outOnRow = x < 0 orelse x >= n
    val outOnCol = y < 0 orelse y >= m
  in
    outOnCol orelse outOnRow
  end;


fun addEdge (x1,y1) (x2,y2) n m T =
  let
    val targetOutside = isOutside (x2,y2) n m
    val u = if targetOutside then (n,m) else (x2,y2)
    val v = (x1,y1)
    (* we will save the edge u -> v *)
    val getAdjList = S.find (T, u)
    val oldAdjList = if getAdjList = NONE then [] else Option.valOf getAdjList
    val newAdjList = v :: oldAdjList
    val newMap = S.insert (T, u, newAdjList)
  in
    newMap
  end;

fun interpretEdge #"U" (x,y) n m T = addEdge (x,y) (x-1,y) n m T
  | interpretEdge #"D" (x,y) n m T = addEdge (x,y) (x+1,y) n m T
  | interpretEdge #"L" (x,y) n m T = addEdge (x,y) (x,y-1) n m T
  | interpretEdge #"R" (x,y) n m T = addEdge (x,y) (x,y+1) n m T
  | interpretEdge _ _ _ _ T = T


fun createGraph n m grid =
  let
    fun addRow _ _ [] T = T
      | addRow i j (c::cs) T =
        let
          val newMap = interpretEdge c (i,j) n m T
        in
          addRow i (j+1) cs newMap
        end;

    fun addAllRows _ [] T = T
      | addAllRows r (row::rows) T =
        let
          val newMap = addRow r 0 row T
        in
          addAllRows (r+1) rows newMap
        end;

  in
    addAllRows 0 grid S.empty
  end;

fun dfs u adj visitedMap =
  let
    val queryAdj = S.find (adj, u);
    val neighbors = if queryAdj = NONE then [] else Option.valOf queryAdj;

    fun visitNeighbors [] visited = visited
      | visitNeighbors (v::vs) visited =
        let
          val queryVisitedMap = S.find (visited, v)
          val isNeighborVisited = queryVisitedMap <> NONE
          val newVisited =
            if isNeighborVisited
            then visited
            else dfs v adj (S.insert (visited, v, []))
        in
          visitNeighbors vs newVisited
        end;

  in
    visitNeighbors neighbors visitedMap
  end;


fun loop_rooms file =
  let
    val p = parse file;
    val n = #1 p;
    val m = #2 p;
    val grid = #3 p;
    val adj = createGraph n m grid;
    val d = dfs (n,m) adj (S.empty);
    val result = n*m - S.numItems d;
  in
    print(Int.toString(result) ^ "\n")
  end;
