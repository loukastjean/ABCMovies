# ABCMovies

Voici mon projet final dans le cours de Développement d'application Web.

ABCMovies te permet d'avoir une interface web uniforme qui regroupe plusieurs vidéos de différents sites de streaming.

Il permet aussi la création de comptes et de l'authentification à deux facteurs.

Quand il est connecté, l'utilisateur peut aussi "like" des vidéos.

Malheureusement, il semblerait que TOU.TV ait changé quelques petites choses dans son encodage de vidéos, donc
ffmpeg a maintenant de la difficulté à merge ces vidéos. Si vous voulez écouter des vidéos, essayez
L'agent Jean, ou encore Stat, mais la majorité des vidéos sur la page d'accueil sont non-fonctionnelles.


## Self-host
```
git clone https://github.com/loukastjean/ABCMovies.git
cd ABCMovies/src
cp -r * ~/public_html/
```

### Sites supportés: 
- TOU.TV
- NOOVO (WIP)
- CRAVE (WIP)

## Comment je fait pour avoir accès aux vidéos, les décrypter puis les stream sur le site
Pour avoir plus d'informations, veuillez consulter mon projet principal:

https://github.com/Nem-git/toutv-downloader/tree/dev

