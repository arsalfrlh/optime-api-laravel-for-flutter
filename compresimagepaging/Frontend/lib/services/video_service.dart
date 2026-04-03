import 'package:video_player/video_player.dart';

class VideoService {
  VideoPlayerController? _controller;

  VideoPlayerController? get controller => _controller;

  Future<void> initialize(String videoUrl)async{
    _controller = VideoPlayerController.networkUrl(Uri.parse("http://192.168.0.103:8000/storage/$videoUrl"));
    await _controller?.initialize();
    _controller?.setLooping(true);
    _controller?.play();
  }

  void play() => _controller?.play();
  void pause() => _controller?.pause();
  Future<void> seek(Duration value) async => _controller?.seekTo(value);
}