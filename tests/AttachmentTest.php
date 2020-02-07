<?php
namespace Tests\PhpMimeMailParser;

use PhpMimeMailParser\Parser;

/**
 * Test Attachment of php-mime-mail-parser
 *
 * Fully Tested Mailparse Extension Wrapper for PHP 5.4+
 *
 */
class AttachmentTest extends TestCase
{
    public function testSaveAttachmentsFromParser()
    {
        $file = __DIR__ . '/mails/m0002';
        $Parser = new Parser();
        $Parser->setPath($file);

        $attachDir = $this->tempdir('m0002_attachments');
        $attachDir .= 'not-yet-existing-directory';

        $Parser->saveAttachments($attachDir);

        $attachmentFiles = glob($attachDir . '*');
        $this->assertCount(1, $attachmentFiles);
    }

    public function testSaveEachAttachment()
    {
        $file = __DIR__ . '/mails/m0002';
        $Parser = new Parser();
        $Parser->setPath($file);

        $attachDir = $this->tempdir('m0002_attachments');

        foreach ($Parser->getAttachments() as $attachment) {
            $attachment->save($attachDir);
        }

        $attachmentFiles = glob($attachDir . '*');
        $this->assertCount(1, $attachmentFiles);
    }

    public function testGeneratingDuplicateSuffixWithoutExtension()
    {
        $file = __DIR__ . '/mails/m0002';
        $Parser = new Parser();
        $Parser->setPath($file);

        $attachDir = $this->tempdir('m0002_attachments');

        $attachments = $Parser->getAttachments();

        $attachments[0]->maxDuplicateNumber = 5;

        for ($i = 1; $i <= 7; $i++) {
            $attachments[0]->save($attachDir);
        }

        $attachmentFiles = glob($attachDir . '*');

        //Original + 5 suffixed + 1 random
        $this->assertCount(7, $attachmentFiles);
        $this->assertFileExists($attachDir . 'attach02');
        $this->assertFileExists($attachDir . 'attach02_1');
        $this->assertFileExists($attachDir . 'attach02_2');
        $this->assertFileExists($attachDir . 'attach02_3');
        $this->assertFileExists($attachDir . 'attach02_4');
        $this->assertFileExists($attachDir . 'attach02_5');
        $this->assertFileNotExists($attachDir . 'attach02_6');
    }

    public function testGeneratingDuplicateSuffix()
    {
        $file = __DIR__ . '/mails/issue115';
        $Parser = new Parser();
        $Parser->setPath($file);

        $attachDir = $this->tempdir('issue115_attachments');

        $attachments = $Parser->getAttachments();

        $attachments[0]->maxDuplicateNumber = 3;

        for ($i = 1; $i <= 5; $i++) {
            $attachments[0]->save($attachDir);
        }

        $attachmentFiles = glob($attachDir . '*');

        //Original + 3 suffixed + 1 random
        $this->assertCount(5, $attachmentFiles);
        $this->assertFileExists($attachDir . 'logo.jpg');
        $this->assertFileExists($attachDir . 'logo_1.jpg');
        $this->assertFileExists($attachDir . 'logo_2.jpg');
        $this->assertFileExists($attachDir . 'logo_3.jpg');
        $this->assertFileNotExists($attachDir . 'logo_4.jpg');
    }

    public function testSavingWithRandomFilenameKeepExtension()
    {
        $file = __DIR__ . '/mails/m0025';
        $Parser = new Parser();
        $Parser->setPath($file);

        $attachDir = $this->tempdir('m0025_attachments');

        $Parser->saveAttachments($attachDir, true, $Parser::ATTACHMENT_RANDOM_FILENAME);

        $attachmentFiles = glob($attachDir . '*');
        $attachmentJpgFiles = glob($attachDir . '*.jpg');
        $attachmentTxtFiles = glob($attachDir . '*.txt');

        $this->assertCount(3, $attachmentFiles);
        $this->assertCount(2, $attachmentJpgFiles);
        $this->assertCount(1, $attachmentTxtFiles);
    }
}
