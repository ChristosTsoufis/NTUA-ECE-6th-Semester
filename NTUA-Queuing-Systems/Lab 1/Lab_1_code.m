clc;
clear all;
close all;
pkg load statistics;

# Poisson Distribution
# 1A
# Probability Mass Function of Poisson processes

k = 0:1:70;
lambda = [3, 10, 30, 50];

for i = 1:columns(lambda)
  poisson(i,:) = poisspdf(k, lambda(i));
endfor

colors = "rbkm";
figure(1);
hold on;
for i = 1:columns(lambda)
  stem(k, poisson(i,:), colors(i), "linewidth", 1.2);
endfor
hold off;

title("Probability Mass Function of Poisson processes");
xlabel("k values");
ylabel("Probability");
legend("lambda = 3", "lambda = 10", "lambda = 30", "lambda = 50");

# 1B
# Mean Value and Variance

index = find(lambda == 30);
chosen = poisson(index,:);
mean_value = 0;
for i = 0:(columns(poisson(index,:))-1)
  mean_value = mean_value + i.*poisson(index, i+1);
endfor

display("mean value of Poisson with lambda 30 is");
display(mean_value);

second_moment = 0;
for i = 0:(columns(poisson(index,:))-1)
  second_moment = second_moment + i.*i.*poisson(index, i+1);
endfor

variance = second_moment - mean_value.^2;
display("Variance of Poisson with lambda 30 is");
display(variance);

# 1C
# Convolution of the Poisson distribution 

first = find(lambda==10);
second = find(lambda==50);
poisson_first = poisson(first,:);
poisson_second = poisson(second,:);

composed = conv(poisson_first, poisson_second);
new_k = 0:1:(2*70);

figure(2);
hold on;
stem(k, poisson_first(:), colors(1), "linewidth", 1.2);
stem(k, poisson_second(:), colors(2), "linewidth", 1.2);
stem(new_k, composed, "mo","linewidth", 2);
hold off;
title("Convolution of two Poisson processes");
xlabel("k values");
ylabel("Probability");
legend("lambda = 10","lambda = 50","New Process");

# 1D
# Poisson process as the limit of the binomial distribution

k = 0:1:200;
# Define the desired Poisson Process
lambda = 30;
i = 1:1:5;
n = lambda.*i; 
p = lambda./n;

figure(3);

hold on;
for i = 1:columns(i)
  binomial = binopdf(k, n(i), p(i));
  stem(k, binomial, colors(i), 'linewidth', 1.2);
endfor
hold off;

title("Poisson process as the limit of the binomial process");
xlabel("k values");
ylabel("Probability");
legend ("n = 30","n = 60","n = 90","n = 120");


# Exponential Distribution
# 2A
# Probability Density Function of Exponential processes

k = 0:0.00001:8;
lambda = [0.5, 1, 3];

for i=1:columns(lambda)
  exponential(i,:) = exppdf(k, lambda(i));
endfor

colors = "rbkm";
figure(4);
hold on;
for i = 1:columns(lambda)
  stem(k, exponential(i,:), colors(i), "linewidth", 1.2);
endfor
hold off;

title("Probability Density Function of Exponential processes");
xlabel("k values");
ylabel("Probability");
legend("1/lambda = 0.5","1/lambda = 1","1/lambda = 3");

# 2B
# Cumulative Distribution Function of Exponential processes

k = 0:0.00001:8;
lambda = [0.5, 1, 3];

for i = 1:columns(lambda)
  exponential_c(i,:) = expcdf(k, lambda(i));
endfor

colors = "rbkm";
figure(5);
hold on;
for i = 1:columns(lambda)
  stem(k, exponential_c(i,:), colors(i), "linewidth", 1.2);
endfor
hold off;

title("Cumulative Distribution Function of Exponential processes");
xlabel("k values");
ylabel("Probability");
legend("1/lambda = 0.5", "1/lambda = 1", "1/lambda = 3");

# 2C
# Memory loss

lambda = 2.5;
expon_c = expcdf(k,lambda);
result1 = 1 - expon_c(30000);
printf("P(X>3000) = %d\n", result1);
result2 = (1 - expon_c(50000))./(1 - expon_c(20000));
printf("P(X>5000|X>2000) = %d\n", result2);

# Counting Process of Poisson Distribution
# 3A
# Poisson Counting Proccess

lambda = 5;
rand_events = exprnd(1./lambda, 1, 100);
temp = 0;
for i = 1:columns(rand_events)
  st(i,:) = rand_events(i) + temp;
  temp = st(i,:);
  Time(i,:) = i;
endfor

figure(6);
stairs(st, Time);
title("Poisson Counting Proccess");
xlabel("Time t (sec)");
ylabel("N(t)");

# 3B
# Average number of events

# Calculation for 100 events
i = rows(Time);
mean_events = i./st(i);
printf("Average number of events (100 events): %d\n", mean_events);

# Calculation for the rest events (200, 300, 500, 1000, 10000)
event = [200, 300, 500, 1000, 10000];

for i = 1:columns(event)
  rand_events = exprnd(1./5, 1, event(i));
  temp = 0;
  for i = 1:columns(rand_events)
    temp = rand_events(i) + temp;
  endfor
  mean_events = columns(rand_events)./temp;
  printf("Average number of events (%d events): ", columns(rand_events));
  printf("%d\n", mean_events);
endfor   