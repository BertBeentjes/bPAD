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
 * Return a piece of content to the frontend
 *
 * @since 0.4.0
 */
class Content extends Respond {
    
    /**
     * construct an content handler, read the command
     * 
     */
    public function __construct() {
        // create the response
        $this->setResponse(new Response());
        // Get the content
        $this->getResponse()->setType('text/plain');
        // create the response
        switch (Request::getCommand()->getCommandMember()) {
            case 'get':
            case 'load': 
                $this->getResponse()->setContent(CacheObjects::getCacheObject(Objects::getObject(Request::getCommand()->getValue()), Request::getCommand()->getContext(), Request::getCommand()->getMode()));
                break;
            case 'instance': 
                // get the parts of the address
                $parts = Request::getCommand()->getItemAddressParts();
                // get the factory for this position
                $instancecontent = new PositionFactory(Objects::getObject($parts[1])->getVersion(Request::getCommand()->getMode())->getPosition($parts[2]), Request::getCommand()->getContext(), Request::getCommand()->getMode());
                // get the instance context for the current context group
                $instancecontext = Contexts::getContextByGroupAndName(Request::getCommand()->getContext()->getContextGroup(), Context::CONTEXT_INSTANCE);
                // now factor the content
                $this->getResponse()->setContent(CacheObjects::getChildObjects($instancecontent->factorInstance($instancecontext, Request::getCommand()->getValue()), Request::getCommand()->getMode()));
                break;
        }
    }
}

?>
