clc;
clear all;
close all;
pkg load queueing; 

#Θεωρητική μελέτη ουράς M/M/1
r = 0:0.01:1;
p57 = (1-r).*(r.^57);
figure(1);
hold on;
plot(r,p57);
hold off;
xlabel('ρ');
ylabel('Πιθανότητα για 57 πελάτες στο σύστημα');

#Ανάλυση ουράς με M/M/1 με Octave
#B
lambda = 5;
mi= 5.01:0.01:10;

for i = 1:columns(mi)
  [U, R, Q, X] = qsmm1(lambda, mi(i));
  util(i,:) = U;
  resp(i,:) = R;
  cli(i,:) = Q;
  thr(i,:) = X;
endfor

figure(2);
hold on;
plot(mi,util, "linewidth", 1.3);
xlabel('Ρυθμός εξυπηρέτησης μ');
ylabel('Βαθμός χρησιμοποίησης (utilization)');
hold off;

figure(3);
hold on;
plot(mi,resp, "linewidth", 1.3);
xlabel('Ρυθμός εξυπηρέτησης μ');
ylabel('Μέσος χρόνος καθυστέρησης του συστήματος Ε(Τ)');
hold off;

figure(4);
hold on;
plot(mi,cli, "linewidth", 1.3);
xlabel('Ρυθμός εξυπηρέτησης μ');
ylabel('Μέσος αριθμός πελατών στο σύστημα');
hold off;

figure(5);
hold on;
plot(mi,thr, "linewidth", 1.3);
xlabel('Ρυθμός εξυπηρέτησης μ');
ylabel('Ρυθμαπόδοση (throughput)');
hold off;

#Σύγκριση συστημάτων με δύο εξυπηρετητές
[U, R, Q, X] = qsmmm(10,10,2);
printf("Μέσος χρόνος καθυστέρης πελάτη (α): %d\n", R);
[U, R, Q, X] = qsmm1(5,10);
printf("Μέσος χρόνος καθυστέρης πελάτη (β): %d\n", R);

#Διαδικασία γεννήσεων θανάτων: εφαρμογή σε σύστημα Μ/Μ/1/Κ
#B
#i
lambda = 5;
mu = 10;

births_B = [lambda, lambda/2, lambda/3, lambda/4];
deaths_D = [mu, mu, mu, mu];

transition_matrix = ctmcbd(births_B, deaths_D);
printf("Η μήτρα ρυθμού μεταβάσεων είναι η εξής\n");
display(transition_matrix);

#ii
P = ctmc(transition_matrix);
printf("Οι εργοδικές πιθανότητες των καταστασεων του συστήματος είναι οι εξής:\n");
for i=1:columns(P)
  printf("P%d = %d\n", i-1, P(i));
endfor

#iii
E = 0;
for i=1:columns(P)
  E = E + (i-1).*P(i)
endfor
printf("Ο μέσος αριθμός πελατών στο σύστημα είναι Ε[n(t)] = %d πελάτες\n", E);

#iv
printf("Η πιθανότητα απόρριψης πελάτη είναι %d \n", P(5));

#v
initial_state = [1,0,0,0,0];
colors = "rbkmg";
for i=1:columns(P);
  index = 0;
  for T = 0 : 0.01 : 50
    index = index + 1;
    Pr = ctmc(transition_matrix, T, initial_state);
    Prob(index) = Pr(i);
    if Pr - P < 0.01
      break;
    endif
  endfor

  T = 0 : 0.01 : T;
  figure(i+5);
  plot(T, Prob, colors(i), "linewidth", 2,...
       T,P(i)*ones(size(T)), colors(i));
        state = sprintf("Διάγραμμα Κατάστασης %d", i-1);
        title(state);
        xlabel("Χρόνος t");
        ylabel ("Πιθανότητα");
        prob = sprintf("p_%d(t)", i-1);
        legend(prob,"Κατάσταση Ισορροπίας", "location", "northeastoutside");
       
endfor

#vi
#i
clear Prob;
lambda = 5;
mu = 1;

births_B = [lambda, lambda/2, lambda/3, lambda/4];
deaths_D = [mu, mu, mu, mu];
transition_matrix = ctmcbd(births_B, deaths_D);
P = ctmc(transition_matrix);

initial_state = [1,0,0,0,0];
colors = "rbkmg";
figure(11);
hold on;
for i=1:columns(P);
  index = 0;
  for T = 0 : 0.01 : 50
    index = index + 1;
    Pr = ctmc(transition_matrix, T, initial_state);
    Prob(index) = Pr(i);
    if Pr - P < 0.01
      break;
    endif
  endfor

  T = 0 : 0.01 : T;
  subplot(3,2,i);
  plot(T, Prob, colors(i), "linewidth", 2,...
       T,P(i)*ones(size(T)), colors(i));
        state = sprintf("Διάγραμμα Κατάστασης %d", i-1);
        title(state);
        xlabel("Χρόνος t");
        ylabel ("Πιθανότητα");
       
endfor
hold off;

#ii
clear Prob;
lambda = 5;
mu = 5;

births_B = [lambda, lambda/2, lambda/3, lambda/4];
deaths_D = [mu, mu, mu, mu];
transition_matrix = ctmcbd(births_B, deaths_D);
P = ctmc(transition_matrix);

initial_state = [1,0,0,0,0];
colors = "rbkmg";
figure(12);
hold on;
for i=1:columns(P);
  index = 0;
  for T = 0 : 0.01 : 50
    index = index + 1;
    Pr = ctmc(transition_matrix, T, initial_state);
    Prob(index) = Pr(i);
    if Pr - P < 0.01
      break;
    endif
  endfor

  T = 0 : 0.01 : T;
  subplot(3,2,i);
  plot(T, Prob, colors(i), "linewidth", 2,...
       T,P(i)*ones(size(T)), colors(i));
        state = sprintf("Διάγραμμα Κατάστασης %d", i-1);
        title(state);
        xlabel("Χρόνος t");
        ylabel ("Πιθανότητα");
       
endfor
hold off;

#iii
clear Prob;
lambda = 5;
mu = 20;

births_B = [lambda, lambda/2, lambda/3, lambda/4];
deaths_D = [mu, mu, mu, mu];
transition_matrix = ctmcbd(births_B, deaths_D);
P = ctmc(transition_matrix);

initial_state = [1,0,0,0,0];
colors = "rbkmg";
figure(13);
hold on;
for i=1:columns(P);
  index = 0;
  for T = 0 : 0.01 : 50
    index = index + 1;
    Pr = ctmc(transition_matrix, T, initial_state);
    Prob(index) = Pr(i);
    if Pr - P < 0.01
      break;
    endif
  endfor

  T = 0 : 0.01 : T;
  subplot(3,2,i);
  plot(T, Prob, colors(i), "linewidth", 2,...
       T,P(i)*ones(size(T)), colors(i));
        state = sprintf("Διάγραμμα Κατάστασης %d", i-1);
        title(state);
        xlabel("Χρόνος t");
        ylabel ("Πιθανότητα");
       
endfor
hold off;