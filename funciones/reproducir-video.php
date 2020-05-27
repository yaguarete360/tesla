<?php if (!isset($_SESSION)) {session_start();}

$nombre_del_video = str_replace("-"," ",$_GET['video']);

$nombre_del_video = ucwords(str_replace(".mp4","",$nombre_del_video));

echo '<h4>'.$nombre_del_video.'</h4>';
echo '<video id="my-video" class="video-js" controls preload="auto" width="800" height="450"';
echo 'poster="MY_VIDEO_POSTER.jpg" data-setup="{}">';
echo '<source src="../../imagenes/videos/'.$_GET['video'].'" type="video/mp4">';
echo '<p class="vjs-no-js">';
  echo 'To view this video please enable JavaScript, and consider upgrading to a web browser that';
  echo '<a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>';
echo '</p>';
echo '</video>';

?>

<script 
	src="http://vjs.zencdn.net/5.0.2/video.js">
</script>
