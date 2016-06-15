<?php

namespace OCA\Deck\Controller;

use OCA\Deck\Service\CardService;

use OCP\IRequest;
use OCP\AppFramework\Controller;

class CardController extends Controller {
    private $userId;
    private $cardService;
    public function __construct($appName,
                                IRequest $request,
                                CardService $cardService,
                                $userId){
        parent::__construct($appName, $request);
        $this->userId = $userId;
        $this->cardService = $cardService;
    }
    /**
     * @NoAdminRequired
     */
    public function index($cardId) {
            return $this->cardService->findAll($boardId);
    }
    /**
     * @NoAdminRequired
     */
    public function read($cardId) {
        return $this->cardService->find($this->userId, $cardId);
    }
    /**
     * @NoAdminRequired
     */
    public function reorder($cardId, $stackId, $order) {
        return $this->cardService->reorder($cardId, $stackId, $order);
    }
        /**
     * @NoAdminRequired
     */
    public function create($title, $stackId, $type, $order=999) {
        return $this->cardService->create($title, $stackId, $type, $order, $this->userId);
    }
    /**
     * @NoAdminRequired
     */
    public function update($id, $title, $stackId, $type, $order) {
            return $this->cardService->update($id, $title, $stackId, $type, $order, $this->userId);
    }
    /**
     * @NoAdminRequired
     */
    public function delete($cardId) {
        return $this->cardService->delete($this->userId, $cardId);
    }
}