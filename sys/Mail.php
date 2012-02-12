<?php

namespace Sys
{
	class Mail
	{
		/**
		 * Mail charset
		 * @var <string>
		 */
		protected $_charset;

		/**
		 * Text version of email
		 * @var <string>
		 */
		protected $_versionText;

		/**
		 * Html version of email
		 * @var <string>
		 */
		protected $_versionHtml;

		/**
		 * Sender email
		 * @var <string>
		 */
		protected $_fromEmail;

		/**
		 * Array holding recipients email
		 * @var <array>
		 */
		protected $_recipients;

		/**
		 * Mail subject
		 * @var <string>
		 */
		protected $_subject;

		/**
		 * Email headers
		 * @var <array>
		 */
		protected $_headers = array();

		/**
		 * Cosntructor
		 * 
		 * @param <string> $charset Sets the email charset
		 */
		public function __construct($charset = 'utf-8')
		{
			$this->setCharset($charset);
		}

		/**
		 * Set mail charset
		 *
		 * @param <string> $charset
		 * @return Mail
		 */
		public function setCharset($charset)
		{
			$this->_charset = $charset;
			return $this;
		}

		/**
		 * Get mail charset
		 * 
		 * @return <string>
		 */
		public function getCharset()
		{
			return $this->_charset;
		}

		/**
		 * Set email sender
		 *
		 * @param <string> $email
		 * @param <string> $name
		 * @return Mail
		 */
		public function setFrom($email, $name = null)
		{
			$this->_fromEmail = $email;
			$this->_setHeader('From', $this->_setAddress($email, $name));
			return $this;
		}

		/**
		 * Add TO recipients
		 * 
		 * @param <string|array> $email Recipient email
		 * @param <string> $name Recipient name
		 * @return Mail
		 */
		public function addRecipient($email, $name = null)
		{
			$this->_addRecipientByType('To', $email, $name);
			return $this;
		}

		/**
		 * Add Cc recipients
		 *
		 * @param <string|array> $email Recipient email
		 * @param <string> $name Recipient name
		 * @return Mail
		 */
		public function addCcRecipient($email, $name = null)
		{
			$this->_addRecipientByType('Cc', $email, $name);
			return $this;
		}

		/**
		 * Add Bcc recipients
		 *
		 * @param <string|array> $email Recipient email
		 * @param <string> $name Recipient name
		 * @return Mail
		 */
		public function addBccRecipient($email, $name = null)
		{
			$this->_addRecipientByType('Bcc', $email, $name);
			return $this;
		}

		/**
		 * Set email subject
		 *
		 * @param <string> $subject
		 * @return Mail
		 */
		public function setSubject($subject)
		{
			$subject = $this->_encodeValue($subject);
			$this->_subject = $subject;
			$this->_setHeader('Subject', $subject);
			return $this;
		}

		/**
		 * Get email subject
		 *
		 * @return <string>
		 */
		public function getSubject()
		{
			return $this->_subject;
		}

		/**
		 * Set email TEXT version
		 *
		 * @param <string> $text
		 * @param <string> $characterSet
		 * @return Mail
		 */
		public function setTextBody($text, $characterSet = 'utf-8')
		{
			if ($characterSet != $this->_charset)
			{
				$this->_versionText = mb_convert_encoding($text, $characterSet, $this->_charset);
			}
			else
			{
				$this->_versionText = $text;
			}
			return $this;
		}

		/**
		 * Set email HTML version
		 *
		 * @param <string> $text
		 * @param <string> $characterSet
		 * @return Mail
		 */
		public function setHtmlBody($text, $characterSet = 'utf-8')
		{
			if ($characterSet != $this->_charset)
			{
				$this->_versionHtml = mb_convert_encoding($text, $characterSet, $this->_charset);
			}
			else
			{
				$this->_versionHtml = $text;
			}
			return $this;
		}

		/**
		 * Adds an email recipient by type
		 *
		 * @param <string> $type Header type, To, Bcc, Cc, etc
		 * @param <string|array> $email Recipient email
		 * @param <string> $name Recipient name
		 */
		protected function _addRecipientByType($type, $email, $name = null)
		{
			if (!is_array($email))
			{
				$name = $this->_encodeValue($name);
				$email = array($name => $email);
			}
			foreach ($email as $recipientName => $recipientEmail)
			{
				$recipientName = $this->_encodeValue($recipientName);
				$this->_setHeader($type, $this->_setAddress($recipientEmail, $recipientName));
				$this->_recipients[$recipientEmail] = $recipientEmail;
			}
		}

		/**
		 * Encode value for email sending
		 *
		 * @param <string> $value Initial value to encode
		 * @param <string> $characterSet Character set to encode to, if not system default
		 * @return <string> Encoded value
		 */
		protected function _encodeValue($value, $characterSet = null)
		{
			$charset = $characterSet;
			if ($charset == null)
			{
				$charset = $this->_charset;
			}
			return mb_encode_mimeheader($value, $charset, "B", "\n");
		}

		/**
		 * Format email address + name
		 * 
		 * @param <string> $email
		 * @param <string> $name
		 * @return <string>
		 */
		protected function _setAddress($email, $name)
		{
			if ($name === null || $name == '' || $name == $email)
			{
				return $email;
			}
			return sprintf('%s <%s>', $name, $email);
		}

		/**
		 * Set email headers
		 *
		 * @param <string> $header
		 * @param <string> $value
		 */
		protected function _setHeader($header, $value)
		{
			if (isset($this->_headers[$header]))
			{
				$this->_headers[$header][] = $value;
			}
			else
			{
				$this->_headers[$header] = array($value);
			}
		}
	}
}
