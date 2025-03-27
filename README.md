# ABCMovies
L'abitibi-ouest la place la plus magnifique, faut pas avoir peur des moustiques, on a beaucoup de sites touristiques et les gens sont tres sympathiques

https://github.com/nilaoda/N_m3u8DL-RE/issues/162
https://github.com/nilaoda/N_m3u8DL-RE/issues/631
https://github.com/nilaoda/N_m3u8DL-RE/issues/583
https://github.com/nilaoda/N_m3u8DL-RE/issues/443

TODO:
mkfifo pipe1
export RE_LIVE_PIPE_OPTIONS=" -c copy -f mpegts pipe: > pipe1"
n-m3u8dl-re URL --live-pipe-mux --mp4-real-time-decryption true --live-keep-segments false -concurrent-download true --del-after-done true --use-ffmpeg-concat-demuxer --key kid:key --key kid:key --key kid:key --save-name NAME --use-shaka-packager true
