<?php
namespace Magepow\Wallpaper\Controller\Index;

use Zend\Log\Filter\Timestamp;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class Post extends \Magento\Framework\App\Action\Action
{
    const XML_PATH_EMAIL_RECIPIENT_NAME = 'trans_email/ident_support/name';
    const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_support/email';
    const FOLDER_LOCATION = 'contactattachment';

    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_scopeConfig;
    protected $_logLoggerInterface;

    private $fileUploaderFactory;

    private $fileSystem;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magepow\Wallpaper\Model\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $loggerInterface,
        UploaderFactory $fileUploaderFactory,
        File $file,
        Filesystem $fileSystem,
        array $data = []

        )
    {
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_logLoggerInterface = $loggerInterface;
        $this->messageManager = $context->getMessageManager();
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->file = $file;
        $this->fileSystem = $fileSystem;

        parent::__construct($context);


    }

    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        try
        {

            $filePath = null;
            $fileName = null;
            $uploaded = false;

            try {
                $fileCheck = $this->fileUploaderFactory->create(['fileId' => 'attachment']);
                $file = $fileCheck->validateFile();
                $attachment = $file['name'] ?? null;
            } catch (\Exception $e) {
                $attachment = null;
            }

            if ($attachment) {
                $upload = $this->fileUploaderFactory->create(['fileId' => 'attachment']);
                $upload->setAllowRenameFiles(true);
                $upload->setFilesDispersion(true);
                $upload->setAllowCreateFolders(true);
                $upload->setAllowedExtensions(['txt', 'csv', 'jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx']);

                $path = $this->fileSystem
                    ->getDirectoryRead(DirectoryList::MEDIA)
                    ->getAbsolutePath(self::FOLDER_LOCATION);
                $result = $upload->save($path);
                $uploaded = self::FOLDER_LOCATION . $upload->getUploadedFilename();
                $filePath = $result['path'] . $result['file'];
                $fileName = $result['name'];
            }
            /** @see \Magento\Contact\Controller\Index\Post::validatedParams() */
            $replyToName = !empty($variables['data']['name']) ? $variables['data']['name'] : null;
            $mimeType = mime_content_type($filePath);
            $this->_inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

            $sender = [
                'name' => $post['name'],
                'email' => $post['email']
            ];
            $sentToEmail = $this->_scopeConfig ->getValue('trans_email/ident_general/email',$storeScope);

            $sentToName = $this->_scopeConfig ->getValue('trans_email/ident_general/name',$storeScope);

            $transport = $this->_transportBuilder
            ->setTemplateIdentifier('wallpaper_email_template')
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
                )
                ->setTemplateVars([
                    'name'  => $post['name'],
                    'email'  => $post['email'],
                    'message' => $post['message']
                ])
                ->setFrom($sender)
                ->addTo($sentToEmail,$sentToName)
                ->createAttachment($this->file->read($filePath), $fileName, $mimeType)
                ->getTransport();
                ;
                $transport->sendMessage();

                $this->_inlineTranslation->resume();
                $this->messageManager->addSuccess('Email sent successfully');
                // $this->_redirect('checkout/cart');
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;

        } catch(\Exception $e){
            $this->messageManager->addError($e->getMessage());
            $this->_logLoggerInterface->debug($e->getMessage());
            exit;
        }

    }
}
