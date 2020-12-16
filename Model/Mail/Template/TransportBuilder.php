<?php

namespace Magepow\Wallpaper\Model\Mail\Template;
use Magento\Framework\App\TemplateTypesInterface;

class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{

    protected $message;
    protected $_attachment;
    protected $_parts = [];

    /**
     * Add an attachment to the message.
     *
     * @param string $content
     * @param string $fileName
     * @param string $fileType
     * @return $this
     */
    public function getAttachment($content, $fileName, $fileType)
    {
        $attachment = new \Zend\Mime\Part($content);
        $attachment->type = $fileType;
        $attachment->disposition = \Zend_Mime::DISPOSITION_ATTACHMENT;
        $attachment->encoding = \Zend_Mime::ENCODING_BASE64;
        $attachment->filename = $fileName;
        return $attachment;
    }

    /**
     * Prepare message.
     *
     * @return $this
     * @throws LocalizedException if template type is unknown
     */
    protected function prepareMessage()
     {
        $template = $this->getTemplate();
        $body = $template->processTemplate();
        switch ($template->getType()) {
            case TemplateTypesInterface::TYPE_TEXT:
                $textPart = new \Zend\Mime\Part();
                $textPart->setContent($body)
                    ->setType(\Zend\Mime\Mime::TYPE_TEXT)
                    ->setCharset('utf-8')
                ;
                $this->_parts[] = $textPart;
                break;

            case TemplateTypesInterface::TYPE_HTML:
                $htmlPart = new \Zend\Mime\Part();
                $htmlPart->setContent($body)
                    ->setType(\Zend\Mime\Mime::TYPE_HTML)
                    ->setCharset('utf-8')
                ;
                $this->_parts[] = $htmlPart;
                break;

            default:
                throw new LocalizedException(
                    new Phrase('Unknown template type')
                );
        }
        parent::prepareMessage();
        $this->setPartsToBody();
        return $this;
    }

    public function createAttachment($content, $fileName, $fileType) {
        if($fileType === null) $fileType = 'image/jpeg,image/gif,image/png,application/pdf,image/x-eps';
        $disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
        $encoding = \Zend\Mime\Mime::ENCODING_BASE64;
        if($fileName === null) throw new \Exception("Param 'filename' can not be null");
        $attachmentPart = new \Zend\Mime\Part();
        $attachmentPart
            ->setContent($content)
            ->setType($fileType)
            ->setDisposition($disposition)
            ->setEncoding($encoding)
            ->setFileName($fileName)
        ;
        $this->_attachment = $attachmentPart;
        return $this;
    }

    public function setPartsToBody() {
        if($this->_attachment) $this->_parts[] = $this->_attachment;
        $mimeMessage = new \Zend\Mime\Message();
        $mimeMessage->setParts($this->_parts);
        $this->message->setBody($mimeMessage);
        return $this;
    }

}
