<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Feed_Reader
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Feed_Reader
 */
require_once 'Zend/Feed/Reader.php';

/**
 * @see Zend_Feed_Reader_Entry_Interface
 */
require_once 'Zend/Feed/Reader/Entry/Interface.php';

/**
 * @see Zend_Feed_Reader_Entry_EntryAbstract
 */
require_once 'Zend/Feed/Reader/Entry/EntryAbstract.php';

/**
 * @see Zend_Feed_Reader_Extension_Atom_Entry
 */
require_once 'Zend/Feed/Reader/Extension/Atom/Entry.php';

/**
 * @category   Zend
 * @package    Zend_Feed_Reader
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Feed_Reader_Entry_Atom extends Zend_Feed_Reader_Entry_EntryAbstract implements Zend_Feed_Reader_Entry_Interface
{
    /**
     * XPath query
     *
     * @var string
     */
    protected $_xpathQuery = '';

    /**
     * Atom Extension object
     *
     * @var Zend_Feed_Reader_Extension_Atom_Entry
     */
    protected $_atom = null;

    /**
     * Thread Extension object
     *
     * @var Zend_Feed_Reader_Extension_Thread_Entry
     */
    protected $_thread = null;

    /**
     * Constructor
     *
     * @param  DOMElement $entry
     * @param  int $entryKey
     * @param  string $type
     * @return void
     */
    public function __construct(DOMElement $entry, $entryKey, $type = null)
    {
        parent::__construct($entry, $entryKey, $type);

        // Everyone by now should know XPath indices start from 1 not 0
        $this->_xpathQuery = '//atom:entry[' . ($this->_entryKey + 1) . ']';
        $atomClass = Zend_Feed_Reader::getPluginLoader()->getClassName('Atom_Entry');

        $this->_atom = new $atomClass($entry, $entryKey, $type);

        $threadClass = Zend_Feed_Reader::getPluginLoader()->getClassName('Thread_Entry');
        $this->_thread = new $threadClass($entry, $entryKey, $type);
    }

	/**
     * Get the specified author
     *
     * @param  int $index
     * @return string|null
     */
    public function getAuthor($index = 0)
    {
        $authors = $this->getAuthors();

        if (isset($authors[$index])) {
            return $authors[$index];
        }

        return null;
    }

    /**
     * Get an array with feed authors
     *
     * @return array
     */
    public function getAuthors()
    {
        if (array_key_exists('authors', $this->_data)) {
            return $this->_data['authors'];
        }

        $people = $this->_atom->getAuthors();

        $this->_data['authors'] = $people;

        return $this->_data['authors'];
    }

    /**
     * Get the entry content
     *
     * @return string
     */
    public function getContent()
    {
        if (array_key_exists('content', $this->_data)) {
            return $this->_data['content'];
        }

        $content = $this->_atom->getContent();

        $this->_data['content'] = $content;

        return $this->_data['content'];
    }

    /**
     * Get the entry creation date
     *
     * @return string
     */
    public function getDateCreated()
    {
        if (array_key_exists('datecreated', $this->_data)) {
            return $this->_data['datecreated'];
        }

        $dateCreated = $this->_atom->getDateCreated();

        $this->_data['datecreated'] = $dateCreated;

        return $this->_data['datecreated'];
    }

    /**
     * Get the entry modification date
     *
     * @return string
     */
    public function getDateModified()
    {
        if (array_key_exists('datemodified', $this->_data)) {
            return $this->_data['datemodified'];
        }

        $dateModified = $this->_atom->getDateModified();

        $this->_data['datemodified'] = $dateModified;

        return $this->_data['datemodified'];
    }

    /**
     * Get the entry description
     *
     * @return string
     */
    public function getDescription()
    {
        if (array_key_exists('description', $this->_data)) {
            return $this->_data['description'];
        }

        $description = $this->_atom->getDescription();

        $this->_data['description'] = $description;

        return $this->_data['description'];
    }

    /**
     * Get the entry enclosure
     *
     * @return string
     */
    public function getEnclosure()
    {
        if (array_key_exists('enclosure', $this->_data)) {
            return $this->_data['enclosure'];
        }

        $enclosure = $this->_atom->getEnclosure();

        $this->_data['enclosure'] = $enclosure;

        return $this->_data['enclosure'];
    }

    /**
     * Get the entry ID
     *
     * @return string
     */
    public function getId()
    {
        if (array_key_exists('id', $this->_data)) {
            return $this->_data['id'];
        }

        $id = $this->_atom->getId();

        $this->_data['id'] = $id;

        return $this->_data['id'];
    }

    /**
     * Get a specific link
     *
     * @param  int $index
     * @return string
     */
    public function getLink($index = 0)
    {
        if (!array_key_exists('links', $this->_data)) {
            $this->getLinks();
        }

        if (isset($this->_data['links'][$index])) {
            return $this->_data['links'][$index];
        }

        return null;
    }

    /**
     * Get all links
     *
     * @return array
     */
    public function getLinks()
    {
        if (array_key_exists('links', $this->_data)) {
            return $this->_data['links'];
        }

        $links = $this->_atom->getLinks();

        $this->_data['links'] = $links;

        return $this->_data['links'];
    }

    /**
     * Get a permalink to the entry
     *
     * @return string
     */
    public function getPermalink()
    {
        return $this->getLink(0);
    }

    /**
     * Get the entry title
     *
     * @return string
     */
    public function getTitle()
    {
        if (array_key_exists('title', $this->_data)) {
            return $this->_data['title'];
        }

        $title = $this->_atom->getTitle();

        $this->_data['title'] = $title;

        return $this->_data['title'];
    }

    /**
     * Get the number of comments/replies for current entry
     *
     * @return integer
     */
    public function getCommentCount()
    {
        if (array_key_exists('commentcount', $this->_data)) {
            return $this->_data['commentcount'];
        }

        $commentcount = $this->_thread->getCommentCount();

        if (!$commentcount) {
            $commentcount = $this->_atom->getCommentCount();
        }

        $this->_data['commentcount'] = $commentcount;

        return $this->_data['commentcount'];
    }

    /**
     * Returns a URI pointing to the HTML page where comments can be made on this entry
     *
     * @return string
     */
    public function getCommentLink()
    {
        if (array_key_exists('commentlink', $this->_data)) {
            return $this->_data['commentlink'];
        }

        $commentlink = $this->_atom->getCommentLink();

        $this->_data['commentlink'] = $commentlink;

        return $this->_data['commentlink'];
    }

    /**
     * Returns a URI pointing to a feed of all comments for this entry
     *
     * @return string
     */
    public function getCommentFeedLink()
    {
        if (array_key_exists('commentfeedlink', $this->_data)) {
            return $this->_data['commentfeedlink'];
        }

        $commentfeedlink = $this->_atom->getCommentFeedLink();

        $this->_data['commentfeedlink'] = $commentfeedlink;

        return $this->_data['commentfeedlink'];
    }

    /**
     * Set the XPath query (incl. on all Extensions)
     *
     * @param DOMXPath $xpath
     */
    public function setXpath(DOMXPath $xpath)
    {
        parent::setXpath($xpath);
        $this->_atom->setXpath($this->_xpath);
        $this->_thread->setXpath($this->_xpath);
        foreach ($this->_extensions as $extension) {
            $extension->setXpath($this->_xpath);
        }
    }
}
