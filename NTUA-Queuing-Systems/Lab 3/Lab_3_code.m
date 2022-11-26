% Simulation of M/M/1/10 simulation

clc;
close all;
clear all;

% Initializing values for the 3 simulations

colors = "cgr";
lambda = [1,5,10];
mu = 5;
k = 10; %queue max capacity

for j=1:columns(lambda)
  
  % Initializing system
  total_arrivals = 0; % to measure the total number of arrivals
  curr_state = 0; % holds the current state of the system
  previous_mean_clients = 0; % will help in the convergence test
  index = 0;

  % Useful variables
  th = lambda(j)/(lambda(j) + mu); % the threshold used to calculate probabilities
  transitions = 0; % holds the transitions of the simulation in transitions steps
  
  rand("seed", 1);
  arrivals = zeros(1,k);
  Pr = zeros(1,k);

  while transitions >= 0
    % Next Transition
    transitions = transitions + 1; % one more transitions step
    
    % Create random number in (0,1) to pick between arrival or departure
    random = rand(1); % generate a random number (Uniform distribution)
    
    #{
    % Strace for debugging purposes (first 30 transitions)
    if transitions <= 30
      if (curr_state == 0 || (random < th && curr_state < k))
        next = "A";
      elseif curr_state != 0
        next = "D";
      endif
      if transitions == 1
        printf("State| Transition| Total Arrivals\n");
      endif
      printf("    %d    |    %s    |     %d     \n", curr_state, next, total_arrivals);
    endif
    #}
    
    if (curr_state == 0 || (random < th && curr_state < k)) % arrival
      % Having new arrival if not reached max queue capacity
      total_arrivals = total_arrivals + 1;
      
      % Increasing arrivals in the current_state
      arrivals(curr_state + 1) = arrivals(curr_state + 1) + 1; % increase the number of arrivals in the current state
            
      % Proceeding to the next state
      curr_state = curr_state + 1;
    
    % departure  
    elseif curr_state != 0 % no departure from an empty system
      % Having new departure if not in an empty state
      curr_state = curr_state - 1;
    endif
    
    % Every 1000 transitions check for convergence
    if mod(transitions,1000) == 0 % check for convergence every 1000 transitions steps
      index = index + 1;
      
      % Updating vector containing probabilities of each state
      for i=1:1:k
        Pr(i) = arrivals(i)/total_arrivals; % calcuate the probability of every state in the system
      endfor
      
      % Calculating mean number of clients in the system
      mean_cl = 0; % calculate the mean number of clients in the system
      for i=1:1:k
        mean_cl = mean_cl + (i-1).* Pr(i);
      endfor
      
      % Keeping values for mean clients graph
      mean_cl_vector(index) = mean_cl;
      
      % Checking for convergence
      if abs(mean_cl - previous_mean_clients) < 0.00001 || transitions > 1000000 % convergence test
        break;
      endif
      
      % Updating previous mean client value
      previous_mean_clients = mean_cl;
    endif
    
  endwhile

  % Printing ergodic probabilities
  figure(2*j-1);
  bar(Pr,colors(j),0.4);
  s = sprintf("Ergodic Probabilities for lambda=%d",lambda(j));
  title(s);
  xlabel("State Number");
  ylabel("Probability");

  % Print mean clients of system till convergence
  figure(2*j);
  plot(mean_cl_vector,colors(j),"linewidth",2,...
       mean_cl*ones(size(mean_cl_vector)),colors(j));
  s = sprintf("Average number of clients in the M/M/1 queue till convergence (lambda=%d)",lambda(j));
  title(s);
  xlabel('Transitions in 10^3');
  ylabel("Average number of clients");
  
  clear mean_cl_vector;
endfor