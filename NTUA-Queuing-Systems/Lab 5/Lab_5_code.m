clc;
clear all;
close all;


# Question 1.1
# Answered in the report

# Question 1.2

x=1;
for a=0.001:0.001:0.999
  E(x)=((20*(a^2)-23*a+15)/(-10*(a^2)+13*a+3))/10000;
  if(x>1)
    if(E(x) < E(x-1))
      minimum_a = a;
      minimum_E = E(x);
    endif
  endif
  x = x + 1;
endfor

# Printing diagram

temp_a=0.001:0.001:0.999;
figure(1);
plot(temp_a, E, "b", "linewidth", 2.5);
xlabel("Parameter a","fontsize", 14);
ylabel("Time (sec)", "fontsize", 14);
legend("E(T)");
title("Average Time Delay E(T) based on a", "fontsize", 16);

display(strcat("The vlaue of a that minimizes E(T) is a= \n",num2str(minimum_a)));
display(strcat("Minimum Average Time E(T)= \n",num2str(minimum_E)));

# Question 2.1
# Answered in the report

# Question 2.2

function [intensity, isErgodic] = intensities(lambda1, lambda2, mu1, mu2, mu3, mu4, mu5)
  isErgodic = 1;
  # Finding intensities
  
  intensity(1) = lambda1/mu1;
  intensity(2) = (((2/7)*lambda1)+lambda2)/mu2;
  intensity(3) = ((4/7)*lambda1)/mu3;
  intensity(4) = ((3/7)*lambda1)/mu4;
  intensity(5) = (((4/7)*lambda1)+lambda2)/mu5;
  # Checking if ergodicity is violated
  
  for i = 1:5
    if(intensity(i)>1)
      isErgodic = 0;
    endif
    # printf("Intensity (ri) %d = %d\n", i, intensity(i));
  endfor

endfunction

# Qeustion 2.3

function MeanClients = mean_clients(lambda1, lambda2, mu1, mu2, mu3, mu4, mu5)
  [Intensity, Ergodic] = intensities(lambda1, lambda2, mu1, mu2, mu3, mu4, mu5);
  for i = 1:5
    MeanClients(i) = Intensity(i) / (1 - Intensity(i));
  endfor

endfunction

# Qeustion 2.4

lambda1 = 4;
lambda2 = 1;
mu1 = 6;
mu2 = 5;
mu3 = 8;
mu4 = 7;
mu5 = 6;

[IntensityTable,ergodic] = intensities(lambda1, lambda2, mu1, mu2, mu3, mu4, mu5);

if ergodic == 1
  printf("The network is ergodic\n");
else
  printf("The network is not ergodic\n");
endif

MeanClientsTable = mean_clients(lambda1, lambda2, mu1, mu2, mu3, mu4, mu5);
Total = 0; # total E(n)

for i = 1:5
  Total = Total + MeanClientsTable(i);
endfor
gamma = lambda1 + lambda2;
AveTimeDelay = Total/gamma; # AveTimeDelay = E(T)

display("Intensities of the system:\n");
display(IntensityTable);
display(strcat("Average Time Delay from edge to edge, is E(T)= \n",num2str(AveTimeDelay)));

# Question 2.5
# Answered in the report

# Question 2.6

j=1;
for lamdba1 = 0.1*6:0.01*6:0.99*6
    tempEnTable = mean_clients(lamdba1,1,6,5,8,7,6);
    tempTotEn = 0;
    for i = 1:5
      tempTotEn += tempEnTable(i);
    endfor
    gamma = lamdba1+1;
    ETrange(j) = tempTotEn/gamma;
    j++;
endfor

templamdba1=0.1*6:0.01*6:0.99*6;
figure(2);
plot(templamdba1,ETrange,"b","linewidth", 2.5);
title("Average Time Delay E(T) edge-to-edge","fontsize", 16);
xlabel("lamdba1(clients/sec)","fontsize", 14);
ylabel("Time(sec)","fontsize", 14);
