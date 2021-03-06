<?php
/**
 * @copyright Copyright (c) 2017 Steven R. Baker <steven@stevenrbaker.com>
 *
 * @author Steven R. Baker <steven@stevenrbaker.com>
 * @author Ryan Fletcher <ryan.fletcher@codepassion.ca>
 *
 * @license GNU AGPL version 3 or any later version
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Deck\Controller;

use OCP\AppFramework\ApiController;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

use OCA\Deck\Service\BoardService;

/**
 * Class BoardApiController
 *
 * @package OCA\Deck\Controller
 */
class BoardApiController extends ApiController {

	private $service;

	/**
	 * @param string $appName
	 * @param IRequest $request
	 * @param BoardService $service
	 * @param $userId
	 */
	public function __construct($appName, IRequest $request, BoardService $service, $userId) {
		parent::__construct($appName, $request);
		$this->service = $service;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 * @CORS
	 * @NoCSRFRequired
	 *
	 * Return all of the boards that the current user has access to.
	 */
	public function index() {
		$modified = $this->request->getHeader('If-Modified-Since');
		if ($modified === '') {
			$boards = $this->service->findAll();
		} else {
			$boards = $this->service->findAll(strtotime($modified));
		}
		return new DataResponse($boards, HTTP::STATUS_OK);
	 }

	/**
	 * @NoAdminRequired
	 * @CORS
	 * @NoCSRFRequired
	 *
	 *
	 * Return the board specified by $this->request->getParam('boardId').
	 */
	public function get() {
		$board = $this->service->find($this->request->getParam('boardId'));
		return new DataResponse($board, HTTP::STATUS_OK);
	}

	/**
	 * @NoAdminRequired
	 * @CORS
	 * @NoCSRFRequired
	 *
	 * @params $title
	 * @params $color
	 *
	 * Create a board with the specified title and color.
	 */
	public function create($title, $color) {
		$board = $this->service->create($title, $this->userId, $color);
		return new DataResponse($board, HTTP::STATUS_OK);
	}

	/**
	 * @NoAdminRequired
	 * @CORS
	 * @NoCSRFRequired
	 *
	 * @params $title
	 * @params $color
	 * @params $archived
	 *
	 * Update a board with the specified boardId, title and color, and archived state.
	 */
	public function update($title, $color, $archived = false) {
		$board = $this->service->update($this->request->getParam('boardId'), $title, $color, $archived);
		return new DataResponse($board, HTTP::STATUS_OK);
	}

	/**
	 * @NoAdminRequired
	 * @CORS
	 * @NoCSRFRequired
	 *
	 *
	 * Delete the board specified by $boardId.  Return the board that was deleted.
	 */
	public function delete() {
		$board = $this->service->delete($this->request->getParam('boardId'));
		return new DataResponse($board, HTTP::STATUS_OK);
	}

	/**
	 * @NoAdminRequired
	 * @CORS
	 * @NoCSRFRequired
	 *
	 *
	 * Undo the deletion of the board specified by $boardId.
	 */
	public function undoDelete() {
		$board = $this->service->deleteUndo($this->request->getParam('boardId'));
		return new DataResponse($board, HTTP::STATUS_OK);
	}

}
