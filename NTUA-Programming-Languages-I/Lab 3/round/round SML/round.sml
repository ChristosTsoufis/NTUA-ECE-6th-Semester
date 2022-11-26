fun round file =
let
  fun parse file =
  let
    fun readInt input =
      Option.valOf (TextIO.scanStream (Int.scan StringCvt.DEC) input)

    val inStream = TextIO.openIn file

    val N = readInt inStream
    val K = readInt inStream

    val _ = TextIO.inputLine inStream

    fun readInts 0 acc = acc
      | readInts i acc = readInts (i - 1) (readInt inStream :: acc)

    val pin=[]
    val list = readInts K pin
  in
    (N, list)
  end

  fun solve (k, l) =
  let
    fun calc start n max =
      if start <= n then n - start
      else (max - start + n)

    fun edl nil n sum max maxcities = (sum, max)
      | edl (h::t) n sum max maxcities =
        let
          val num = calc h n maxcities
        in
          if num > max then edl t n (sum+num) num maxcities else edl t n (sum+num) max maxcities
        end

    fun sum nil = 0
      | sum (h::t) = h + sum t

    fun lmax nil acc = acc
      | lmax (h::t) acc = if h > acc then lmax t h else lmax t acc


    fun checkstate (h::t) n k =
    let
      val (sum, max) = edl (h::t) n 0 0 k
    in
      if 2*max < sum + 2 then (sum,n) else (~1,~1)
    end

    fun all l acc ~1 max = acc
      | all l acc k max =
        let
          val (c,t) = checkstate l k max
        in
          (all l ((c,t)::acc) (k-1) max)
        end

    fun res (nil, min, minindex) =  print (Int.toString min ^ " " ^ Int.toString minindex) (*(min, minindex)*)
      | res ((h,p)::t, min, minindex) =
        if (h < min andalso h <> ~1) then res (t, h, p)
        else res (t, min, minindex)

    val (l::t) = (all l [] (k-1) k)
    
  in
    res (l::t, #1 l, 0)
  end
in
  solve (parse file)
end;
