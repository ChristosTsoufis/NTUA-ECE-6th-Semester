import java.io.*;
import java.util.*;

public class Round{

   protected static int N,K;
   protected static int [] cities,cars;

	public static void main(String[] args) throws FileNotFoundException {
      Scanner S = new Scanner(new File(args[0]));
      // N cities
      N = S.nextInt();
      // K cars
      K = S.nextInt();
      
      cars = new int[K];
      cities = new int[N];

      // Finds which city has each car
      for (int i = 0; i < K; i++)     
         cars[i] = S.nextInt();
      
      // Finds how many cars there are in each city
      for (int i = 0; i < N; i++){    
         cities[i] = 0;
      }

      // Finds how many cars each city has
      for (int i = 0; i < K; i++){   
         cities[cars[i]] += 1;
      }
      
      int [] Temp = new int [K];
      for (int i=0; i<K; i++){
            if (cars[i] != 0) {
            Temp[i] = N-cars[i];
            }
      }

      int [] in  = {0,0,0};
      
      for (int i=0; i<K; i++){
            in[0] += Temp[i];
            if (Temp[i] > in[1]){
               in[1] = Temp[i];
            }
      }
      in[2] = N-in[1];
      

      int sum,max,maxtown,nsum;
      int [] answer = new int [2];
      sum = answer[0] = in[0];
      answer[1] = 0;
      max = in[1];
      maxtown = in[2];


      for (int i=1; i < N; i++){
         
         nsum = sum+K-cities[i]*N;
         
         if (maxtown != i){
               max++;
         }
         else{
               maxtown = find_next(i+1);
               if (i > maxtown){
                  max = i-maxtown;
               }
               else{
                  max = N+i-maxtown;
               }
         }

         if (nsum+1 > 2*max){
            if (nsum < answer[0]){
               answer[0] = nsum;
               answer[1] = i;
            }
         }

         sum = nsum;
      }

      System.out.println(answer[0]+" "+answer[1]);
   }

   private static int find_next(int itr){
      int i;
      for(i=itr; i<N; i++){
         if (cities[i] != 0){
            break;
         }
         if (i == N-1){
            i = -1;
         }
      }

      return i;
   }
}
