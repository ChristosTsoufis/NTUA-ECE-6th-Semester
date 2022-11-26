/* Solution based on the famous gabbage, goat, wolf problem: https://courses.softlab.ntua.gr/pl1/2019a/Labs/goat.tgz */

import java.io.*;     // for I/O methods
import java.util.*;

// This is our main class. It has to be the first, in alphabetical order, in order to be accepted by the Grader. 
public class QSsort {
  // The main function.
  public static void main(String args[]) {
    String inputArg = args[0];
      
		try {
        // reading the input
		    BufferedReader in = new BufferedReader(new FileReader(inputArg));
        String line = in.readLine ();
        String [] a = line.split (" ");
        int N = Integer.parseInt(a[0]); // first line (N)

        line = in.readLine ();  // second line (the initial queue)
        a = line.split (" ");

        // data structures. We use ArrayLists for everything
        ArrayList<Integer> queue = new ArrayList<Integer>();
        ArrayList<Integer> stack = new ArrayList<Integer>();
        
        
        // Parse the initial queue to the ArrayList
        for (int i = 0; i < N; i++)
        queue.add(Integer.parseInt(a[i]));
        in.close (); //close the buffer
        
        ArrayList<Integer> sortedQueue = (ArrayList<Integer>)queue.clone(); // note that we have to use clone. 
        String response = "";
        
        // sortedQueue=queue;
        Collections.sort(sortedQueue);  // Sort the queue
        
        // ---------
        
        Solver solver = new ZBFSolver(); //BFS solver 
        State initial = new ZSState(stack, sortedQueue, queue, response, null); //create initial state
        State result = solver.solve(initial); // call the solver

      // check result
        if (result.toString() == "") {
          System.out.println("empty");
        } else {
          System.out.println(result);
        }
		} catch (IOException e) {
			e.printStackTrace();
		}
  }
}
