<?php
/**
 * Application: bPAD
 * Author: Bert Beentjes
 * Copyright: Copyright Bert Beentjes 2010-2014
 * http://www.bertbeentjes.nl, http://www.bpadcms.nl
 * 
 * This file is part of the bPAD content management system.
 * 
 * bPAD is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * bPAD is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with bPAD.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * In the factory, bPAD terms are interpreted and replaced by the specified content
 * 
 * There are several types of terms:
 * 
 * 1. connector terms; a term enclosed in hash signs, like #co# or #pn#
 * 2. attribute terms; zero or more selectors and ending in an attribute name, connected with dots and enclosed in hash signs, like #id# (or #this.id#), #parent.id# or #parent.parent.author#
 * 3. embed terms; embed specific objects in a specific location, with a specific context, connected with pipes and enclosed in hash signs, like #1|2# (objectid or reference, context)
 * 
 * embed terms are usually system created, but in certain cases the user can use this to show content in a specific place or for example add page metadata in the header
 * 
 * The factory handles several types of content:
 * 
 * 1. content; the content from a snippet or other type of file
 * 2. object; an object, with the layout holding the basic snippet
 * 3. object position; the position of an object, with the structure holding the basic snippet
 *
 * @since 0.4.0
 */
class Factory {
    private $content;
    private $context;
    private $mode;
    
    /**
     * constructor for the factory
     * 
     */
    public function __construct() {
        // constructor
    }
    
    /**
     * factor the content into the result
     * 
     */
    public function factor() {
        // factor
    }
    
    /**
     * Set the content string to factor
     * 
     * @param string $newcontent
     */
    public function setContent($newcontent) {
        $this->content = $newcontent;
    }
    
    /**
     * Get the content string from the factory
     * 
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set the context string to factor
     * 
     * @param string $newcontext
     */
    public function setContext($newcontext) {
        $this->context = $newcontext;
    }
    
    /**
     * Set the context as the default context for this context group
     * 
     * @param string $contextgroup
     */
    public function setContextForContextGroup($contextgroup) {
        $this->context = Contexts::getContextByGroupAndName($contextgroup, Context::CONTEXT_DEFAULT);
    }

    /**
     * Get the context string from the factory
     * 
     * @return context
     */
    public function getContext() {
        return $this->context;
    }

    /**
     * Set the mode string to factor
     * 
     * @param string $newmode
     */
    public function setMode($newmode) {
        $this->mode = $newmode;
    }
    
    /**
     * Get the mode string from the factory
     * 
     * @return mode
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * check for a term in the content
     * 
     * @return boolean true if found
     */
    protected function hasTerm($term) {
        return (strpos($this->getContent(), $term) > -1);
    }
    
    /**
     * replace a term in the content
     * 
     */
    protected function replaceTerm($term, $replacement) {
        $this->setContent(str_replace($term, $replacement, $this->getContent()));
    }
    
    /**
     * replace a term once in the content
     * 
     * @param string $term
     * @param string $replacement
     */
    protected function replaceTermOnce($term, $replacement) {
        $occurrence = strpos($this->getContent(), $term);
        $this->setContent(substr_replace($this->getContent(), $replacement, strpos($this->getContent(), $term), strlen($term)));
    }
    
    /**
     * replace a term in the content
     * 
     * @param string $string
     * @param string $term
     * @param string $replacement
     * @return string
     */
    protected function replaceTermInString($string, $term, $replacement) {
        return str_replace($term, $replacement, $string);
    }
    
    /**
     * replace the term by nothing (e.g. when the user isn't authorized to see this)
     * 
     */
    protected function clearTerm($term) {
        $this->replaceTerm($term, '');
    }

}