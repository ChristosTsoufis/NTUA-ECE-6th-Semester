clc;
clear all;
close all;
pkg load queueing;
 
% M/M/N/K System (Call Center)

% Question 2

mu = 1/4;
lambda = [0.25, 1];

% system with capacity 8 states
states = [0, 1, 2, 3, 4, 5, 6, 7, 8];
% the initial state of the system. The system is initially empty.
initial_state = [1, 0, 0, 0, 0, 0, 0, 0, 0];

for j = 1:columns(lambda)
  % define the birth rates between the states of the system.
  births_B = [lambda(j), lambda(j), lambda(j), lambda(j), lambda(j), lambda(j), lambda(j), lambda(j)];
  % define the death rates between the states of the system.
  deaths_D = [mu, 2 * mu, 3 * mu, 4 * mu, 5 * mu, 5 * mu, 5 * mu, 5 * mu];

  % get the transition matrix of the birth-death process
  transition_matrix = ctmcbd(births_B, deaths_D);
  % get the ergodic probabilities of the system
  P = ctmc(transition_matrix);
  
  printf("Ergodic Probabilities for lambda=%d \n", lambda(j));
  for i = 1:columns(states)
    printf("P%d = %d \n", states(i), P(i));
  endfor
  printf("\n");
  % Pwait = P5 + P6 +P7
  prob_waiting(j) = P(6) + P(7) + P(8);
endfor

% Question 3

printf("\n");
printf("Pwait = %d  for lambda=1\n", prob_waiting(2));
printf("\n");
printf("Pwait = %d  for lambda=0.25\n", prob_waiting(1));
printf("\n");
printf("Pwait = %d  for lambda=1 with erlangc\n", erlangc(lambda(2)/mu, 5));
printf("\n");
printf("Pwait = %d  for lambda=0.25 with erlangc\n", erlangc(lambda(1)/mu, 5));

% Analysing & Designing a Call Center

% Question 1

% Test for the accuracy of erlangb_factorial
function p_block = erlangb_factorial(r,c)
  % when k is 0, denominator is 1
  denominator = 1;
  for k = 1:c
    denominator += ((r.^k)./(factorial(k)));
  endfor
  p_block = (((r.^c)./(factorial(c)))./denominator);
endfunction

printf("\n");
printf("Erlang-B factorial=%d\n", erlangb_factorial(1.2, 8));
printf("Erlang-B          =%d\n", erlangb(1.2, 8));

% Question 2

% Test for the accuracy erlangb_recursive
function p_block_recursive = erlangb_recursive(r,c)
  if(c == 0)
    p_block_recursive = 1;
  else
    rec = erlangb_recursive(r,c-1);
    p_block_recursive = (r.*rec)./(r.*rec.+c);
  endif
endfunction

% Test for the accuracy erlangb_iterative
function p_block_iterative = erlangb_iterative(r,c)
  p_block_iterative = 1;
  for n = 0:c
    p_block_iterative = (r.*p_block_iterative)./(p_block_iterative.*r.+n);
  endfor
endfunction

printf("\n");
printf("Erlang-B recursive=%d\n",(erlangb_recursive(1.2,8)));
printf("Erlang-B iterative=%d\n",(erlangb_iterative(1.2,8)));
printf("Erlang-B          =%d\n",(erlangb(1.2, 8)));

% Question 3

printf("\n");
printf("Erlang-B factorial(1024,1024) = %d\n", erlangb_factorial(1024,1024));
printf("Erlang-B iterative(1024,1024) = %d\n", erlangb_iterative(1024,1024));
% printf("Erlang-B recursive(1024,1024) = %d\n", erlangb_recursive(1024,1024));
printf("\n");

% Question 4

% b
% Diagram of Client Rejection Probability
no_lines = 200
ro = 200 .* 23 ./ 60;
Pbl = zeros(1,200);
for i = 1:201
  Pbl(i) = erlangb_iterative(ro,i);
  if Pbl(i) <= 0.01 && no_lines == 200
    no_lines = i
  endif
endfor

% Printing Ergodic Probabilities
figure(1);
bar(Pbl,'b',0.4);
title("Pblocking based on Phone Lines");
xlabel("Number of Phone Lines");
ylabel("Pblocking");

% Minimum number of Phone Lines
printf("\n");
printf("Minimum number of Phone Lines = %d\n", no_lines);  

% Service Systems with two dissimilar servers

% Question 2

clear all;

lambda = 1;
m1 = 0.8;
m2 = 0.4;

threshold_0 = 1;
threshold_1a = lambda / (lambda + m1);
threshold_1b = lambda / (lambda + m2);
threshold_2_first = lambda / (lambda + m1 + m2);
threshold_2_second = (m1 + lambda) / (lambda + m1 + m2);

current_state = 0;
arrivals = zeros(1,4); % arrivals in each state of the system
total_arrivals = 0; % The total number of client arrivals in the system
maximum_state_capacity = 2; % maximum number of clients in the system
previous_mean_clients = 0; % estimated mean delay in the previous time interval
delay_counter = 0;
time = 0;

while 1 > 0
  time = time + 1;
  
  if mod(time,1000) == 0
    for i=1:1:4
      P(i) = arrivals(i)/total_arrivals;
    endfor
    
    delay_counter = delay_counter + 1;

    mean_clients = 0*P(1) + 1*P(2) + 1*P(3) + 2*P(4);   
    
    delay_table(delay_counter) = mean_clients;
    
    if abs(mean_clients - previous_mean_clients) < 0.00001
       break;
    endif
    previous_mean_clients = mean_clients;
  endif
  
  random_number = rand(1);
  
  if current_state == 0
    if random_number < threshold_0
      current_state = 1;
      arrivals(1) = arrivals(1) + 1;
      total_arrivals = total_arrivals + 1;
    endif
  elseif current_state == 1 %state 1 = 11
    if random_number < threshold_1a
      current_state = 3; %state 3 = 2
      arrivals(2) = arrivals(2) + 1;
      total_arrivals = total_arrivals + 1;
    else
      current_state = 0;
    endif
  elseif current_state == 2 %state 2 = 12
    if random_number < threshold_1b 
      current_state = 3; %state 3 = 2
      arrivals(3) = arrivals(3) + 1;
      total_arrivals = total_arrivals + 1;
    else
      current_state = 0;
    endif
  else %state 3 = 2
      if random_number < threshold_2_first
        arrivals(4) = arrivals(4) + 1;
        total_arrivals = total_arrivals + 1;
      elseif random_number < threshold_2_second
        current_state = 2;
      else
        current_state = 1;
      endif
   endif
  
endwhile

printf("\n");
printf("P0  = %d\n",P(1));
printf("P11 = %d\n",P(2));
printf("P12 = %d\n",P(3));
printf("P2  = %d\n",P(4));
printf("Pblocking = %d\n",P(4));
average = 0*P(1) + 1*P(2) + 1*P(3) + 2*P(4);
printf("Avergae Clients in the System = %d\n",average);
