<?php
namespace Simope;
class EntityManager
{
    public function __construct($config)
    {
        $this->config = $config;
    }
    public function persist($entity)
    {
        if (is_object($entity) === false) {
            throw new Exception\EntityManagerException('Entity should be an object');
        }
        $genClass = $this->config->id_gen_strategy_class;
        if (!isset($entity->id)) {
            $entity->id = $genClass::generate();
        }
        if (
            is_dir($this->config->dir) === false
            || is_writable($this->config->dir) === false
        ) {
            throw new Exception\EntityManagerException('Could not create entity direcory');
        }
        if (!is_dir($this->config->dir.'/'.get_class($entity))) {
            mkdir($this->config->dir.'/'.get_class($entity));
        }
        file_put_contents(
            sprintf(
                '%s/%s/%s.json',
                $this->config->dir,
                get_class($entity),
                $entity->id
            ),
            json_encode($entity)
        );
        $index = new Index($this->config);
        $index->set($entity);
    }
    public function remove($entity)
    {
        return unlink(
            sprintf(
                '%s/%s/%s.json',
                $this->config->dir,
                get_class($entity),
                $entity->id
            )
        );
    }
    public function find($class, $entityId)
    {
        $file = sprintf(
            '%s/%s/%s.json',
            $this->config->dir,
            $class,
            $entityId
        );
        if (file_exists($file)
        ) {
            return json_decode(file_get_contents($file));
        }
    }
    public function findBy($class, $key, $value)
    {
        $index = new Index($this->config);
        $ids = $index->get($class, $key, $value);
        $result = array();
        foreach ($ids as $id) {
            $file = sprintf(
                '%s/%s/%s.json',
                $this->config->dir,
                $class,
                $id
            );
            if (file_exists($file)
            ) {
                $result[] = json_decode(file_get_contents($file));
            }
        }
        return $result;
    }
    public function spy($class, $entityId)
    {
        $file = sprintf(
            '%s/%s/%s.json',
            $this->config->dir,
            $class,
            $entityId
        );
        if (file_exists($file)
        ) {
            $file = new \SplFileInfo($file);
            $result = array();
            $result['last_modified_time'] = $file->getATime();
            $result['size']               = $file->getSize();
            return $result;
        } else {
            return null;
        } 
    }
}
