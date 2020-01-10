function FctIni(n) { // appelée par index.html
  switch (n) { // Initialisation de plusieurs figures connues
  case 0:
    TIT="Vide"; X=50; Y=48; // dimensions de la grille (recommandé: 16 à 500)
    D=8; // dimension de la cellule en pixels (recommandé: 1 à 20)
    CLR0='#000000'; CLR1='#FFFFFF'; // couleur morte, vivante
    Clear(); // toutes les cellules sont mortes
    return;
  case 1:
    TIT="Clown"; X=50; Y=48; D=8; CLR0='#000077'; CLR1='#FFFF77'; Clear();
    CC[1124]=CC[1125]=CC[1126]=CC[1174]=CC[1176]=CC[1224]=CC[1226]=1;
    return;
  case 2:
    TIT="Ligne"; X=200; Y=151; D=3; CLR0='#000000'; CLR1='#77FFFF'; Clear();
    i=X*Math.floor(Y/2); m=i+X; do CC[i]=1; while (++i<m);
    return;
  case 3:
    TIT="Gosper glider gun"; X=100; Y=87; D=8; CLR0='#000077'; CLR1='#FF7777';
    Clear();
    i=X; CC[i+25]=1;
    i+=X; CC[i+23]=CC[i+25]=1;
    i+=X; CC[i+13]=CC[i+14]=CC[i+21]=CC[i+22]=CC[i+35]=CC[i+36]=1;
    i+=X; CC[i+12]=CC[i+16]=CC[i+21]=CC[i+22]=CC[i+35]=CC[i+36]=1;
    i+=X; CC[i+1]=CC[i+2]=CC[i+11]=CC[i+17]=CC[i+21]=CC[i+22]=1;
    i+=X; CC[i+1]=CC[i+2]=CC[i+11]=CC[i+15]=CC[i+17]=CC[i+18]=CC[i+23]=CC[i+25]=1;
    i+=X; CC[i+11]=CC[i+17]=CC[i+25]=1;
    i+=X; CC[i+12]=CC[i+16]=1;
    i+=X; CC[i+13]=CC[i+14]=1;
   return;
  case 4:
    TIT="Achimsp144"; X=32; Y=21; D=20; CLR0='#000000'; CLR1='#77FF77'; Clear();
    CC[34]=CC[35]=CC[60]=CC[61]=CC[66]=CC[67]=CC[92]=CC[93]=1;
    CC[116]=CC[117]=CC[147]=CC[150]=CC[180]=CC[181]=1;
    CC[208]=CC[239]=CC[241]=CC[270]=CC[274]=CC[302]=CC[305]=1;
    CC[366]=CC[369]=CC[397]=CC[401]=CC[430]=CC[432]=CC[463]=1;
    CC[490]=CC[491]=CC[521]=CC[524]=CC[554]=CC[555]=1;
    CC[578]=CC[579]=CC[604]=CC[605]=CC[610]=CC[611]=CC[636]=CC[637]=1;
    return;
  case 5:
    TIT="Mathusalhem: Le gland"; X=200; Y=200; D=4;
    CLR0='#000077'; CLR1='#FFFF77'; Clear();
    i=Math.floor(7*X/10)+X*Math.floor(3*Y/5); CC[i]=CC[i+X+2]=1;
    i+=2*X; CC[i-1]=CC[i]=CC[i+3]=CC[i+4]=CC[i+5]=1;
    return;
  case 6:
    TIT="Mathusalhem: Les lapins"; X=200; Y=200; D=4;
    CLR0='#000077'; CLR1='#77FF77'; Clear();
    i=Math.floor(2*X/5)+X*Math.floor(5*Y/11); CC[i]=CC[i+2]=1;
    i+=X; CC[i-4]=CC[i-2]=CC[i+1]=1;
    i+=X; CC[i-3]=CC[i+1]=1;
    i+=X; CC[i-3]=CC[i+3]=1;
    return;
  case 7:3
    TIT="Pantomino R"; X=91; Y=91; D=8; CLR0='#000077'; CLR1='#FF7777'; Clear();
    i=Math.floor(X/2)+X*Math.floor(Y/2); CC[i]=CC[i+X]=CC[i+X+1]=1;
    i+=2*X; CC[i-1]=CC[i]=1;
    return;
  case 8:
    TIT="Thunderbird"; X=61; Y=37; D=8; CLR0='#000000'; CLR1='#7777FF'; Clear();
    i=12*X+Math.floor(X/2);
    CC[i-1]=CC[i]=CC[i+1]=CC[i+2*X]=CC[i+3*X]=CC[i+4*X]=1;
    return;
  case 9:
    TIT="Aléatoire"; X=91; Y=91; D=8; CLR0='#000000'; CLR1='#FF77FF'; Clear();
    n=X*Y/3; do CC[Math.floor(X*Y*Math.random())]=1; while (--n>0);
    return;
  }
}
function Clear() {i=X*Y; do CC[--i]=0; while (i>0);}