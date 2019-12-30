<?php




use Wisdom\CallVoice\Voice\VoiceAdd;

class Call
{
    protected $voice;

    public function __construct($data)
    {
        $this->voice = new VoiceAdd($data);
    }

    /**
     * 发起语音机器人
     * @return mixed
     * @throws \Wisdom\CallVoice\Exceptions\HttpException
     * @throws \Wisdom\CallVoice\Exceptions\InvalidArgumentException
     */
    public function call()
    {
        return $this->voice->start();
    }
}